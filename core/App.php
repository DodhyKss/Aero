<?php

namespace Core;

/**
 * App - Main Application Class
 * 
 * Handles bootstrapping the framework, managing the request lifecycle,
 * and dispatching routes for the PHP SPA framework.
 */
class App
{
    private static ?App $instance = null;
    private Router $router;
    private Request $request;
    private array $config = [];
    private array $layouts = [];
    private ?string $currentLayout = 'default';

    private function __construct()
    {
        $this->request = new Request();
        $this->router = new Router();
    }

    /**
     * Get the singleton App instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the Router instance
     */
    public function router(): Router
    {
        return $this->router;
    }

    /**
     * Get the Request instance
     */
    public function request(): Request
    {
        return $this->request;
    }

    /**
     * Set configuration values
     */
    public function setConfig(string $key, mixed $value): self
    {
        $this->config[$key] = $value;
        return $this;
    }

    /**
     * Get configuration value
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Register a layout
     */
    public function registerLayout(string $name, string $path): self
    {
        $this->layouts[$name] = $path;
        return $this;
    }

    /**
     * Set the current layout to use
     */
    public function setLayout(string $name): self
    {
        $this->currentLayout = $name;
        return $this;
    }

    /**
     * Run the application
     */
    public function run(): void
    {
        try {
            $request = $this->request;
            $isAjax = $request->isAjax();
            $uri = $request->getUri();
            $method = $request->getMethod();

            // Resolve the route
            $result = $this->router->resolve($uri, $method);

            if ($result === null) {
                // 404 - Not Found
                if ($isAjax) {
                    Response::html($this->render404(), 404);
                } else {
                    $this->renderFullPage($this->render404());
                }
                return;
            }

            // Extract route info
            $callback = $result['callback'];
            $params = $result['params'];
            $middleware = $result['middleware'] ?? [];
            $layout = $result['layout'] ?? $this->currentLayout;

            // Run middleware chain
            foreach ($middleware as $mw) {
                $middlewareResult = $this->runMiddleware($mw, $request);
                if ($middlewareResult !== true) {
                    if ($isAjax) {
                        Response::json(['error' => 'Middleware blocked', 'redirect' => $middlewareResult], 403);
                    } else {
                        header("Location: " . ($middlewareResult ?: '/'));
                        exit;
                    }
                    return;
                }
            }

            // Execute the route callback
            $content = $this->executeCallback($callback, $params, $request);

            if ($isAjax) {
                // For AJAX requests, return only the HTML fragment
                Response::html($content);
            } else {
                // For full page loads, wrap in layout
                $this->currentLayout = $layout;
                $this->renderFullPage($content);
            }
        } catch (\Throwable $e) {
            $isAjax = $this->request->isAjax();
            if ($isAjax) {
                Response::html($this->render500($e), 500);
            } else {
                $this->renderFullPage($this->render500($e));
            }
        }
    }

    /**
     * Execute a route callback
     */
    private function executeCallback(mixed $callback, array $params, Request $request): string
    {
        if (is_callable($callback)) {
            ob_start();
            $result = call_user_func_array($callback, array_merge([$request], $params));
            $output = ob_get_clean();
            return $result ?? $output;
        }

        if (is_string($callback)) {
            // "Controller@method" syntax
            if (str_contains($callback, '@')) {
                [$class, $method] = explode('@', $callback);
                if (!class_exists($class)) {
                    $class = "App\\Pages\\{$class}";
                }
                if (class_exists($class)) {
                    $instance = new $class();
                    ob_start();
                    $result = call_user_func_array([$instance, $method], array_merge([$request], $params));
                    $output = ob_get_clean();
                    return $result ?? $output;
                }
            }

            // Treat as a view file path
            return View::render($callback, ['request' => $request, 'params' => $params]);
        }

        return '';
    }

    /**
     * Run a middleware
     */
    private function runMiddleware(mixed $middleware, Request $request): mixed
    {
        if (is_callable($middleware)) {
            return $middleware($request);
        }

        if (is_string($middleware) && class_exists($middleware)) {
            $instance = new $middleware();
            if (method_exists($instance, 'handle')) {
                return $instance->handle($request);
            }
        }

        return true;
    }

    /**
     * Render the full HTML page with layout
     */
    private function renderFullPage(string $content): void
    {
        $layoutPath = $this->layouts[$this->currentLayout] ?? null;

        if ($layoutPath && file_exists($layoutPath)) {
            // Use registered layout
            $pageContent = $content;
            $app = $this;
            include $layoutPath;
        } else {
            // Use default built-in layout
            echo $this->getDefaultLayout($content);
        }
    }

    /**
     * Get the default layout HTML
     */
    private function getDefaultLayout(string $content): string
    {
        $title = $this->getConfig('app_name', 'Aero Framework');
        $baseUrl = $this->getConfig('base_url', '');
        $apiBaseUrl = $this->getConfig('api_base_url', $baseUrl . '/api');
        
        return <<<HTML
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$title}</title>
            <link rel="icon" type="image/svg+xml" href="{$baseUrl}/assets/favicon.svg">
            <link rel="stylesheet" href="{$baseUrl}/assets/css/bootstrap-icons.css">
            <link rel="stylesheet" href="{$baseUrl}/assets/css/tailwind.css">
        </head>
        <body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">
            <div id="spa-app" class="flex-grow flex flex-col">
                <div id="spa-content" class="flex-grow">{$content}</div>
            </div>
            <div id="spa-loading" class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-[9999] flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none" style="display:none;">
                <div class="w-12 h-12 border-4 border-indigo-500/30 border-t-indigo-600 rounded-full animate-spin"></div>
            </div>
            <script>
                window.APP_CONFIG = {
                    baseUrl: '{$baseUrl}',
                    apiBaseUrl: '{$apiBaseUrl}'
                };
            </script>
            <script src="{$baseUrl}/assets/js/api-service.js"></script>
            <script src="{$baseUrl}/assets/js/spa-engine.js"></script>
            <script src="{$baseUrl}/assets/js/app.js"></script>
        </body>
        </html>
        HTML;
    }

    /**
     * Render the 404 page
     */
    private function render404(): string
    {
        $viewPath = $this->getConfig('views_path', '') . '/errors/404.php';
        if (file_exists($viewPath)) {
            return View::render('errors/404');
        }

        return <<<HTML
        <div data-spa-title="404 Not Found" class="min-h-[80vh] flex items-center justify-center p-4">
            <div class="text-center max-w-md">
                <div class="text-9xl font-black text-slate-200 mb-4">404</div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Halaman Tidak Ditemukan</h1>
                <p class="text-slate-500 mb-8">Maaf, halaman atau URL yang Anda cari mungkin telah dihapus atau Anda salah mengetikkan alamat.</p>
                <a href="/" data-spa-link class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors gap-2">
                    <i class="bi bi-house-door-fill"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
        HTML;
    }

    /**
     * Render the 500 error page
     */
    private function render500(\Throwable $e): string
    {
        $viewPath = $this->getConfig('views_path', '') . '/errors/500.php';
        if (file_exists($viewPath)) {
            return View::render('errors/500', ['exception' => $e]);
        }

        $debug = ini_get('display_errors') || $_SERVER['SERVER_NAME'] === 'localhost';
        $message = $debug ? $e->getMessage() : 'Telah terjadi kesalahan tak terduga pada server kami.';
        $trace = $debug ? "<pre class='mt-6 p-4 bg-slate-900 text-rose-300 rounded-lg text-left text-xs overflow-x-auto'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>" : "";

        return <<<HTML
        <div data-spa-title="500 Server Error" class="min-h-[80vh] flex items-center justify-center p-4">
            <div class="text-center max-w-2xl">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-rose-100 text-rose-500 text-5xl mb-6">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-800 mb-3">Terjadi Kesalahan (500)</h1>
                <p class="text-slate-600">{$message}</p>
                {$trace}
                <div class="mt-8">
                    <a href="/" data-spa-link class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-slate-800 text-white font-medium hover:bg-slate-900 transition-colors gap-2">
                        <i class="bi bi-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
        HTML;
    }
}
