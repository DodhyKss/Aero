# 🚀 Aero Framework

Aero adalah framework frontend minimalis berbasis PHP murni dengan navigasi Single Page Application (SPA) tanpa *full page reload*. Framework ini tidak membutuhkan Composer, dirancang untuk menjadi sangat ringan, dan dilengkapi dengan utilitas API Fetching serta styling menggunakan **Tailwind CSS** & **Bootstrap Icons** secara offline.

## ✨ Fitur Utama

- 🛤️ **SPA Navigation**: Navigasi halaman yang mulus menggunakan AJAX dan History API.
- 🎨 **Tailwind Offline**: Menggunakan Tailwind CSS yang dikompilasi (via NPM). Bebas *lag* dan bisa diakses 100% offline tanpa CDN.
- 📡 **Client & Server API**: Dilengkapi dengan helper `Api` untuk Javascript dan `HttpClient` untuk backend PHP.
- 🧩 **Komponen Reusable**: Pecah kode UI kamu menjadi komponen-komponen kecil berbasis PHP.
- 🛡️ **Proteksi Middleware**: Dukungan middleware bawaan untuk proteksi *route* (contoh: pengecekan sesi / Auth).
- ⚙️ **Konfigurasi .env**: Dukungan pembacaan variabel *environment* melalui `Core\Env` untuk menyimpan *secret key* atau URL API secara aman.

## 📥 Instalasi dari Awal

Pastikan komputer kamu sudah terinstall **PHP (minimal versi 8.0)** dan **Node.js / NPM**.

1. **Clone atau Download Repository**
   ```bash
   git clone https://github.com/username/Aero-framework.git
   cd Aero-framework
   ```

2. **Install Dependencies Frontend**
   Perintah ini akan mendownload package NPM (seperti *Tailwind CSS* dan *Bootstrap Icons*) yang dibutuhkan agar framework bisa berjalan secara offline.
   ```bash
   npm install
   ```

3. **Kompilasi CSS Pertama Kali**
   Generate file CSS utuh di public sesuai dengan utility classes HTML yang ada.
   ```bash
   npm run css:build
   ```

4. **Jalankan Development Server**
   Buka terminal/CMD baru, lalu jalankan PHP server bawaan ke arah folder `public/`:
   ```bash
   php -S localhost:8080 -t public
   ```
   Buka browser kamu di `http://localhost:8080`!

## 📁 Struktur Direktori

```text
framework/
 ├── app/
 │    ├── Components/    # Potongan UI reusable (navbar, footer, sidebar)
 │    ├── Middleware/    # Class untuk proteksi rute sebelum diakses
 │    ├── Pages/         # Halaman-halaman aplikasi (View)
 │    └── routes.php     # Definisi semua rute/URL web
 ├── core/               # Mesin utama framework (Router, App, View, Request, dll)
 ├── public/             # Document root (hanya folder ini yang diakses browser)
 │    ├── assets/        # CSS, JS, dan Fonts yang sudah di-build
 │    └── index.php      # Entry point (Front Controller)
 ├── src/                # File stylesheet mentahan (input) untuk Tailwind
 └── tailwind.config.js  # Konfigurasi Tailwind CSS
```

## 🛤️ Routing & SPA Navigation

Semua rute dideklarasikan di dalam file `app/routes.php`.

```php
use Core\View;

$router->get('/', function () {
    return View::render('home');
});

$router->get('/tentang', function () {
    return View::render('about');
});
```

**Cara kerja SPA Navigation:**
Jangan gunakan link `<a>` biasa. Tambahkan atribut `data-spa-link` ke dalam tag `<a>` HTML kamu agar framework me-nol-kan perilaku *default* browser dan melakukan transisi halaman tanpa *reload*.

```html
<a href="/tentang" data-spa-link class="text-indigo-500 hover:underline">
    Ke Halaman Tentang
</a>
```

## 🛡️ Proteksi Middleware

Jika kamu ingin membatasi akses pada rute tertentu (misal: halaman Admin), gunakan fitur Middleware. Framework ini mendukung middleware baik pada *Group* maupun *Single Route*.

```php
use App\Middleware\AuthMiddleware;

// Opsi 1: Menerapkan pada Group (banyak rute sekaligus)
$router->group(['prefix' => '/admin', 'middleware' => [AuthMiddleware::class]], function ($router) {
    $router->get('/dashboard', function() {
        return "Ini halaman admin rahasia.";
    });
});

// Opsi 2: Menerapkan langsung pada Single Route
$router->get('/profile', function() {
    return "Halaman Profil";
})->middleware(AuthMiddleware::class);
```

*Contoh file Middleware bisa kamu lihat di `app/Middleware/AuthMiddleware.php`.*

## 🧩 Views & Components

Pecah rancangan UI kamu menjadi potongan-potongan di `app/Components/` (contoh: `navbar.php`), lalu panggil di dalam View Utama (`app/Pages/home.php`) menggunakan metode `View::component()`.

```php
<!-- app/Pages/home.php -->
<?php use Core\View; ?>

<!-- Set judul browser secara dinamis saat navigasi SPA -->
<div data-spa-title="Halaman Home - Aero">
    
    <!-- Memanggil Navbar -->
    <?= View::component('navbar') ?>

    <main class="p-4 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold">Halo Dunia!</h1>
        <p>Selamat datang di Aero Framework.</p>
    </main>

</div>
```

## 📡 Fetching Data API Terpusat

Dalam SPA, perpindahan halaman tidak perlu *reload*, jadi seringkali kita perlu mengambil data dari Back-End via Javascript.
Untuk mempermudah pemanggilan HTTP Request, gunakan JS object `Api` (pembungkus API `fetch()` asli bawaan JS).

```javascript
// Contoh pemanggilan dari JS frontend
const result = await window.Api.get('https://jsonplaceholder.typicode.com/users/1');

if (result.success) {
    console.log(result.data);
    // Fungsi Toast notification bawaan framework
    showToast('Berhasil mengambil data!', 'success');
} else {
    showToast('Terjadi kesalahan', 'error');
}
```

## 🛠️ Pengembangan CSS (Tailwind)

Aero sudah pre-configured dengan Tailwind CSS. Selama proses *coding* atau mendesain, jalankan perintah *watch* pada terminal agar setiap penambahan class `.php` kamu langsung dikompilasi:

```bash
npm run css:watch
```

---
*Dibuat dengan ❤️ oleh Aero.*
