/**
 * ApiService - Client-side API Helper
 * 
 * Fetch data dari API eksternal langsung dari browser.
 * Cocok untuk SPA karena data di-load secara dinamis tanpa reload.
 * 
 * Cara pakai:
 *   Api.setBaseUrl('https://api.example.com');
 *   Api.setToken('your-jwt-token');
 *   
 *   const users = await Api.get('/users');
 *   const newUser = await Api.post('/users', { name: 'John' });
 */
const Api = {
    baseUrl: (typeof window !== 'undefined' && window.APP_CONFIG && window.APP_CONFIG.apiBaseUrl) ? window.APP_CONFIG.apiBaseUrl : '',
    token: null,
    defaultHeaders: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },

    /**
     * Set base URL untuk semua request
     */
    setBaseUrl(url) {
        this.baseUrl = url.replace(/\/+$/, '');
    },

    /**
     * Set Bearer token
     */
    setToken(token) {
        this.token = token;
    },

    /**
     * Hapus token (logout)
     */
    clearToken() {
        this.token = null;
    },

    /**
     * GET request
     */
    async get(url, params = {}) {
        const query = new URLSearchParams(params).toString();
        const fullUrl = query ? `${url}?${query}` : url;
        return this.request('GET', fullUrl);
    },

    /**
     * POST request
     */
    async post(url, data = {}) {
        return this.request('POST', url, data);
    },

    /**
     * PUT request
     */
    async put(url, data = {}) {
        return this.request('PUT', url, data);
    },

    /**
     * DELETE request
     */
    async delete(url) {
        return this.request('DELETE', url);
    },

    /**
     * Upload file via FormData
     */
    async upload(url, formData) {
        const fullUrl = this.baseUrl ? `${this.baseUrl}/${url.replace(/^\//, '')}` : url;
        const headers = {};
        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        try {
            const response = await fetch(fullUrl, {
                method: 'POST',
                headers,
                body: formData,
            });

            const data = await response.json();
            return { success: response.ok, status: response.status, data };
        } catch (error) {
            return { success: false, status: 0, error: error.message, data: null };
        }
    },

    /**
     * Core request method
     */
    async request(method, url, body = null) {
        const fullUrl = this.baseUrl ? `${this.baseUrl}/${url.replace(/^\//, '')}` : url;

        const headers = { ...this.defaultHeaders };
        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        const options = { method, headers };
        if (body && ['POST', 'PUT', 'PATCH'].includes(method)) {
            options.body = JSON.stringify(body);
        }

        try {
            const response = await fetch(fullUrl, options);
            const text = await response.text();
            let data;

            try {
                data = JSON.parse(text);
            } catch {
                data = text;
            }

            if (!response.ok) {
                return {
                    success: false,
                    status: response.status,
                    data,
                    error: data?.message || `HTTP ${response.status}`,
                };
            }

            return { success: true, status: response.status, data };
        } catch (error) {
            return { success: false, status: 0, error: error.message, data: null };
        }
    },
};

// Expose globally
window.Api = Api;
