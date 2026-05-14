/**
 * App.js - Application-level JavaScript
 * 
 * Custom hooks, event listeners, and app-specific JavaScript logic.
 */
(function () {
    'use strict';

    // Wait for SPA engine to be available
    if (!window.SPA) {
        console.warn('SPA engine not loaded');
        return;
    }

    // ============================
    // Lifecycle Hooks
    // ============================

    // Before navigation - can return false to cancel
    SPA.on('beforeNavigate', ({ from, to }) => {
        console.log(`📍 Navigating: ${from} → ${to}`);
    });

    // After navigation complete
    SPA.on('afterNavigate', ({ url }) => {
        // Update active navigation links
        updateActiveNav(url);
    });

    // On page content loaded (both initial and SPA navigations)
    SPA.on('onLoad', ({ url, initial }) => {
        initInteractiveElements();
    });

    // On error
    SPA.on('onError', ({ url, error }) => {
        console.error(`❌ Error loading: ${url}`, error);
        showToast('Terjadi kesalahan saat memuat halaman', 'error');
    });

    // ============================
    // Navigation Helpers
    // ============================

    /**
     * Update active state on navigation links
     */
    function updateActiveNav(currentUrl) {
        document.querySelectorAll('.nav-link, .sidebar-link').forEach((link) => {
            const href = link.getAttribute('href');
            if (href === currentUrl || (href !== '/' && currentUrl.startsWith(href))) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    /**
     * Initialize interactive elements on new content
     */
    function initInteractiveElements() {
        // Initialize dropdowns, tooltips, modals, etc.
        initDropdowns();
        initModals();
        initTooltips();
    }

    // ============================
    // UI Components
    // ============================

    /**
     * Simple dropdown toggle
     */
    function initDropdowns() {
        document.querySelectorAll('[data-dropdown]').forEach((trigger) => {
            if (trigger._dropdownInit) return;
            trigger._dropdownInit = true;

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const target = document.querySelector(trigger.dataset.dropdown);
                if (target) {
                    target.classList.toggle('show');
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu.show').forEach((menu) => {
                menu.classList.remove('show');
            });
        });
    }

    /**
     * Modal handling
     */
    function initModals() {
        // Open modal
        document.querySelectorAll('[data-modal-open]').forEach((trigger) => {
            if (trigger._modalInit) return;
            trigger._modalInit = true;

            trigger.addEventListener('click', () => {
                const modal = document.querySelector(trigger.dataset.modalOpen);
                if (modal) {
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        // Close modal
        document.querySelectorAll('[data-modal-close]').forEach((trigger) => {
            if (trigger._modalCloseInit) return;
            trigger._modalCloseInit = true;

            trigger.addEventListener('click', () => {
                const modal = trigger.closest('.modal-overlay');
                if (modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
    }

    /**
     * Tooltip initialization
     */
    function initTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach((el) => {
            if (el._tooltipInit) return;
            el._tooltipInit = true;

            el.addEventListener('mouseenter', () => {
                const tip = document.createElement('div');
                tip.className = 'fixed z-[9999] px-2.5 py-1 text-xs font-medium text-white bg-slate-800 rounded-lg shadow-md transition-opacity duration-200 opacity-0 pointer-events-none whitespace-nowrap';
                tip.textContent = el.dataset.tooltip;
                document.body.appendChild(tip);

                const rect = el.getBoundingClientRect();
                tip.style.top = rect.top - tip.offsetHeight - 8 + 'px';
                tip.style.left = rect.left + (rect.width - tip.offsetWidth) / 2 + 'px';

                el._tooltip = tip;
                requestAnimationFrame(() => tip.classList.replace('opacity-0', 'opacity-100'));
            });

            el.addEventListener('mouseleave', () => {
                if (el._tooltip) {
                    el._tooltip.classList.replace('opacity-100', 'opacity-0');
                    setTimeout(() => el._tooltip?.remove(), 200);
                    el._tooltip = null;
                }
            });
        });
    }

    // ============================
    // Toast Notifications
    // ============================

    function showToast(message, type = 'info', duration = 3000) {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container fixed top-6 right-6 flex flex-col gap-3 z-[9999]';
            document.body.appendChild(container);
        }

        const typeColors = {
            success: 'text-emerald-500',
            error: 'text-rose-500',
            warning: 'text-amber-500',
            info: 'text-sky-500'
        };
        const color = typeColors[type] || typeColors.info;

        const toast = document.createElement('div');
        toast.className = `flex items-center justify-between p-4 rounded-xl bg-white shadow-xl min-w-[300px] border border-slate-100 transform translate-x-[120%] transition-transform duration-300`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="font-bold text-lg ${color}">${getToastIcon(type)}</span>
                <span class="text-sm font-medium text-slate-700">${message}</span>
            </div>
            <button class="text-slate-400 hover:text-slate-600 transition-colors ml-4 text-xl leading-none" onclick="this.parentElement.remove()">&times;</button>
        `;

        container.appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-x-[120%]'));

        setTimeout(() => {
            toast.classList.add('translate-x-[120%]');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    function getToastIcon(type) {
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ',
        };
        return icons[type] || icons.info;
    }

    // Listen for custom spa:contentLoaded event
    document.addEventListener('spa:contentLoaded', (e) => {
        initInteractiveElements();
    });

    // Expose utilities globally
    window.showToast = showToast;

    // Initialize on first load
    updateActiveNav(window.location.pathname);
    initInteractiveElements();
})();
