<?php

/**
 * Routes Definition
 * 
 * Define all application routes here.
 * Available methods: get, post, put, delete, patch
 * 
 * Usage:
 *   $router->get('/path', callback, 'route-name');
 *   $router->get('/path', 'PageClass@method');
 *   $router->get('/path', function($request) { return 'html'; });
 *   
 *   Route groups:
 *   $router->group(['prefix' => '/admin', 'middleware' => [...]], function($router) {
 *       $router->get('/dashboard', ...);
 *   });
 */

use Core\App;
use Core\View;

$app = App::getInstance();
$router = $app->router();

// ============================
// Public Routes
// ============================

$router->get('/', function () {
    return View::render('home');
}, 'home');

// ============================
// Contoh Penggunaan Middleware
// ============================
use App\Middleware\AuthMiddleware;

/*
// Contoh 1: Menerapkan Middleware pada Group (Route Rahasia)
$router->group(['prefix' => '/admin', 'middleware' => [AuthMiddleware::class]], function ($router) {
    $router->get('/dashboard', function() {
        return "Ini halaman admin rahasia. Hanya bisa diakses jika AuthMiddleware mereturn true.";
    });
});

// Contoh 2: Menerapkan Middleware Langsung pada satu Route
$router->get('/profile', function() {
    return "Halaman Profil";
})->middleware(AuthMiddleware::class);
*/
