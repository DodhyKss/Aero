<?php
namespace App\Middleware;

use Core\Request;

/**
 * Contoh Middleware untuk mengecek apakah user sudah login.
 * Jika belum, kembalikan rute/URL untuk redirect.
 * Jika sudah, kembalikan true agar request dilanjutkan.
 */
class AuthMiddleware
{
    public function handle(Request $request): mixed
    {
        // Contoh sederhana: Cek session atau header token
        // Di aplikasi sungguhan, gunakan $_SESSION atau $_COOKIE yang sesuai
        $isLoggedIn = isset($_SESSION['user_id']); // Contoh
        
        // Agar demo berjalan, kita asumsikan user belum login dan request akan diblokir
        // Kecuali ini diset true secara manual
        $isLoggedIn = false;

        if (!$isLoggedIn) {
            // Jika false/belum login, kembalikan string URL untuk di-redirect
            // Jika diakses via SPA (AJAX), framework akan mengembalikan JSON error 403.
            return '/login'; 
        }

        // Return true jika diizinkan
        return true;
    }
}
