/**
 * SPA Engine - Core JavaScript for PHP SPA Framework
 * 
 * Handles client-side routing via History API, AJAX page fetching,
 * page transitions, link interception, form handling, and lifecycle hooks.
 */
(function () {
    'use strict';

    const SPA = {
        // Configuration
        config: {
            contentSelector: '#spa-content',
            loadingSelector: '#spa-loading',
            linkSelector: 'a[data-spa-link], a:not([target]):not([data-spa-ignore]):not([href^="http"]):not([href^="mailto:"]):not([href^="tel:"]):not([href^="#"]):not([href^="javascript:"])',
            formSelector: 'form[data-spa-form]',
            transitionDuration: 300,
            scrollToTop: true,
            enablePrefetch: true,
        },

        // State
        state: {
            currentUrl: window.location.pathname + window.location.search,
            isNavigating: false,
            cache: new Map(),
            hooks: {
                beforeNavigate: [],
                afterNavigate: [],
                onError: [],
                onLoad: [],
            },
            prefetchedUrls: new Set(),
        },

        /**
         * Initialize the SPA engine
         */
        init() {
            // Bind event listeners
            this.bindLinks();
            this.bindForms();
            this.bindPopState();

            // Enable prefetching on hover
            if (this.config.enablePrefetch) {
                this.bindPrefetch();
            }

            // Run onLoad hooks for initial page
            this.runHooks('onLoad', { url: this.state.currentUrl, initial: true });

            // Execute any inline scripts in the initial content
            this.executeScripts(document.querySelector(this.config.contentSelector));

            console.log('%c Aero Framework Engine Initialized', 'color: #6366f1; font-weight: bold; font-size: 14px;');
        },

        /**
         * Bind click events for SPA navigation links
         */
        bindLinks() {
            document.addEventListener('click', (e) => {
                const link = e.target.closest(this.config.linkSelector);
                if (!link) return;

                const href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;

                // Allow modifier keys to work normally (open in new tab, etc.)
                if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;

                e.preventDefault();
                this.navigate(href);
            });
        },

        /**
         * Bind form submissions for SPA handling
         */
        bindForms() {
            document.addEventListener('submit', (e) => {
                const form = e.target.closest(this.config.formSelector);
                if (!form) return;

                e.preventDefault();
                this.submitForm(form);
            });
        },

        /**
         * Bind browser back/forward buttons
         */
        bindPopState() {
            window.addEventListener('popstate', (e) => {
                const url = window.location.pathname + window.location.search;
                this.loadPage(url, false);
            });
        },

        /**
         * Bind prefetching on hover
         */
        bindPrefetch() {
            document.addEventListener('mouseover', (e) => {
                const link = e.target.closest(this.config.linkSelector);
                if (!link) return;

                const href = link.getAttribute('href');
                if (!href || this.state.prefetchedUrls.has(href)) return;

                this.state.prefetchedUrls.add(href);
                this.prefetchPage(href);
            });
        },

        /**
         * Navigate to a new URL
         */
        async navigate(url, pushState = true) {
            if (this.state.isNavigating) return;
            if (url === this.state.currentUrl) return;

            // Run beforeNavigate hooks
            const allowed = await this.runHooks('beforeNavigate', {
                from: this.state.currentUrl,
                to: url,
            });

            if (allowed === false) return;

            await this.loadPage(url, pushState);
        },

        /**
         * Load a page via AJAX
         */
        async loadPage(url, pushState = true) {
            this.state.isNavigating = true;
            this.showLoading();

            try {
                const content = await this.fetchPage(url);

                if (content === null) {
                    throw new Error('Empty response');
                }

                // Check for SPA redirect
                if (typeof content === 'object' && content._spa_redirect) {
                    this.state.isNavigating = false;
                    this.hideLoading();
                    await this.navigate(content.url);
                    return;
                }

                // Animate transition
                await this.transitionContent(content);

                // Update URL
                if (pushState) {
                    window.history.pushState({ url }, '', url);
                }

                this.state.currentUrl = url;

                // Scroll to top
                if (this.config.scrollToTop) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

                // Update page title from response
                this.updateTitle(content);

                // Run afterNavigate hooks
                this.runHooks('afterNavigate', { url, content });

                // Run onLoad hooks
                this.runHooks('onLoad', { url, initial: false });

            } catch (error) {
                console.error('SPA Navigation Error:', error);
                this.runHooks('onError', { url, error });

                // Fallback: full page reload
                // window.location.href = url;
            } finally {
                this.state.isNavigating = false;
                this.hideLoading();
            }
        },

        /**
         * Fetch a page's HTML content via AJAX
         */
        async fetchPage(url) {
            // Check cache first
            if (this.state.cache.has(url)) {
                const cached = this.state.cache.get(url);
                if (Date.now() - cached.time < 30000) { // 30 second cache
                    return cached.content;
                }
                this.state.cache.delete(url);
            }

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-SPA-Request': 'true',
                    'Accept': 'text/html',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const contentType = response.headers.get('Content-Type') || '';

            if (contentType.includes('application/json')) {
                return await response.json();
            }

            const html = await response.text();

            // Cache the response
            this.state.cache.set(url, {
                content: html,
                time: Date.now(),
            });

            return html;
        },

        /**
         * Prefetch a page in the background
         */
        async prefetchPage(url) {
            try {
                await this.fetchPage(url);
            } catch (e) {
                // Silently fail prefetch
            }
        },

        /**
         * Transition the page content with animation
         */
        async transitionContent(newContent) {
            const container = document.querySelector(this.config.contentSelector);
            if (!container) return;

            // Fade out
            container.style.opacity = '0';
            container.style.transform = 'translateY(10px)';

            await this.wait(this.config.transitionDuration / 2);

            // Update content
            container.innerHTML = newContent;

            // Execute scripts in new content
            this.executeScripts(container);

            // Re-initialize any dynamic elements
            this.initDynamicElements(container);

            // Fade in
            requestAnimationFrame(() => {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            });

            await this.wait(this.config.transitionDuration / 2);
        },

        /**
         * Execute scripts found in dynamically loaded content
         */
        executeScripts(container) {
            if (!container) return;

            const scripts = container.querySelectorAll('script');
            scripts.forEach((oldScript) => {
                const newScript = document.createElement('script');

                // Copy attributes
                Array.from(oldScript.attributes).forEach((attr) => {
                    newScript.setAttribute(attr.name, attr.value);
                });

                if (oldScript.src) {
                    newScript.src = oldScript.src;
                } else {
                    newScript.textContent = oldScript.textContent;
                }

                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
        },

        /**
         * Initialize dynamic elements in new content
         */
        initDynamicElements(container) {
            // Dispatch a custom event for user code to hook into
            const event = new CustomEvent('spa:contentLoaded', {
                detail: { container },
                bubbles: true,
            });
            document.dispatchEvent(event);
        },

        /**
         * Submit a form via AJAX
         */
        async submitForm(form) {
            const url = form.action || window.location.href;
            const method = (form.method || 'POST').toUpperCase();
            const formData = new FormData(form);

            this.showLoading();

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-SPA-Request': 'true',
                    },
                    body: formData,
                });

                const contentType = response.headers.get('Content-Type') || '';

                if (contentType.includes('application/json')) {
                    const data = await response.json();

                    if (data._spa_redirect) {
                        await this.navigate(data.url);
                        return;
                    }

                    // Dispatch form response event
                    const event = new CustomEvent('spa:formResponse', {
                        detail: { data, form, status: response.status },
                        bubbles: true,
                    });
                    form.dispatchEvent(event);
                } else {
                    const html = await response.text();
                    await this.transitionContent(html);
                }
            } catch (error) {
                console.error('SPA Form Error:', error);
                this.runHooks('onError', { url, error, type: 'form' });
            } finally {
                this.hideLoading();
            }
        },

        /**
         * Update the page title from response content
         */
        updateTitle(content) {
            const titleMatch = content.match(/data-spa-title="([^"]+)"/);
            if (titleMatch) {
                document.title = titleMatch[1];
            }
        },

        /**
         * Show loading indicator
         */
        showLoading() {
            const loader = document.querySelector(this.config.loadingSelector);
            if (loader) {
                loader.style.display = 'flex';
                requestAnimationFrame(() => {
                    loader.classList.remove('opacity-0', 'pointer-events-none');
                    loader.classList.add('opacity-100', 'pointer-events-auto');
                });
            }
        },

        /**
         * Hide loading indicator
         */
        hideLoading() {
            const loader = document.querySelector(this.config.loadingSelector);
            if (loader) {
                loader.classList.remove('opacity-100', 'pointer-events-auto');
                loader.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 300);
            }
        },

        /**
         * Register a lifecycle hook
         */
        on(event, callback) {
            if (this.state.hooks[event]) {
                this.state.hooks[event].push(callback);
            }
            return this;
        },

        /**
         * Remove a lifecycle hook
         */
        off(event, callback) {
            if (this.state.hooks[event]) {
                this.state.hooks[event] = this.state.hooks[event].filter(
                    (cb) => cb !== callback
                );
            }
            return this;
        },

        /**
         * Run lifecycle hooks
         */
        async runHooks(event, data) {
            if (!this.state.hooks[event]) return;

            for (const hook of this.state.hooks[event]) {
                const result = await hook(data);
                if (result === false) return false;
            }
        },

        /**
         * Clear the navigation cache
         */
        clearCache() {
            this.state.cache.clear();
            this.state.prefetchedUrls.clear();
        },

        /**
         * Programmatic navigation helper
         */
        go(url) {
            this.navigate(url);
        },

        /**
         * Go back in history
         */
        back() {
            window.history.back();
        },

        /**
         * Go forward in history
         */
        forward() {
            window.history.forward();
        },

        /**
         * Utility: wait for a given duration
         */
        wait(ms) {
            return new Promise((resolve) => setTimeout(resolve, ms));
        },
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => SPA.init());
    } else {
        SPA.init();
    }

    // Expose to global scope
    window.SPA = SPA;
})();
