<?php

/**
 * Entry Point - public/index.php
 * 
 * All requests are routed through this file.
 * It bootstraps the framework and dispatches the request.
 */

// Define base paths
define('ROOT_PATH', dirname(__DIR__));
define('CORE_PATH', ROOT_PATH . '/core');
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/Pages');
define('COMPONENTS_PATH', APP_PATH . '/Components');
define('PUBLIC_PATH', __DIR__);

// Simple PSR-4-like autoloader
spl_autoload_register(function (string $class) {
    $prefixes = [
        'Core\\' => CORE_PATH . '/',
        'App\\Pages\\' => VIEWS_PATH . '/',
        'App\\Components\\' => COMPONENTS_PATH . '/',
        'App\\Middleware\\' => APP_PATH . '/Middleware/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (str_starts_with($class, $prefix)) {
            $relativeClass = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Serve static files in PHP built-in server
if (php_sapi_name() === 'cli-server') {
    $requestUri = $_SERVER['REQUEST_URI'];
    $filePath = PUBLIC_PATH . parse_url($requestUri, PHP_URL_PATH);
    if ($requestUri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
        return false; // Let the built-in server handle static files
    }
}

use Core\App;
use Core\Env;

// Load environment variables
require_once CORE_PATH . '/Env.php';
Env::load(ROOT_PATH . '/.env');

// Bootstrap the application
$app = App::getInstance();

// App configuration
$app->setConfig('app_name', Env::get('APP_NAME', 'Aero Framework'));
$app->setConfig('base_url', '');
$app->setConfig('api_base_url', Env::get('API_BASE_URL', ''));
$app->setConfig('views_path', VIEWS_PATH);

// Set HttpClient base URL for Backend API logic (opsional jika dibutuhkan)
\Core\HttpClient::setBaseUrl(Env::get('API_BASE_URL', ''));

// Load routes
require_once APP_PATH . '/routes.php';

// Run the application
$app->run();
