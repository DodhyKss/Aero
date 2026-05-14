<?php use Core\View; ?>
<div data-spa-title="Dokumentasi - Aero">

<?= View::component('navbar') ?>

<main class="max-w-4xl mx-auto px-4 py-12">
    <!-- Header -->
    <header class="text-center mb-16 animate-fade-in-up">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold mb-6">
            <i class="bi bi-rocket-takeoff-fill"></i>
            Frontend PHP Framework
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-4 tracking-tight">Aero Framework</h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto">Framework frontend minimalis berbasis PHP murni dengan navigasi Single Page Application (SPA) tanpa full page reload. Dilengkapi API Fetching dan styling menggunakan Tailwind CSS & Bootstrap Icons offline.</p>
    </header>

    <div class="space-y-12">
        
        <!-- Section: Fitur Utama -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="bi bi-stars text-indigo-500"></i> Fitur Utama
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center shrink-0 text-xl">
                        <i class="bi bi-router-fill"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">SPA Navigation</h3>
                        <p class="text-sm text-slate-600 mt-1">Navigasi halaman mulus menggunakan AJAX dan History API. Halaman tidak pernah reload.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 text-xl">
                        <i class="bi bi-palette-fill"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Tailwind Offline</h3>
                        <p class="text-sm text-slate-600 mt-1">Menggunakan Tailwind CSS yang dikompilasi offline (via NPM). Tampilan modern dan super cepat.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 text-xl">
                        <i class="bi bi-hdd-network-fill"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Client & Server API</h3>
                        <p class="text-sm text-slate-600 mt-1">Gunakan helper <code>Api</code> di browser (JavaScript) atau <code>HttpClient</code> di server (PHP).</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 text-xl">
                        <i class="bi bi-puzzle-fill"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Komponen Reusable</h3>
                        <p class="text-sm text-slate-600 mt-1">Pecah kode menjadi komponen PHP kecil (navbar, footer) dan render dengan mudah.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Instalasi -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="bi bi-download text-indigo-500"></i> Instalasi dari Awal
            </h2>
            <div class="space-y-4">
                <p class="text-slate-600 mb-4">Framework ini dirancang se-ringan mungkin tanpa menggunakan Composer. Kamu hanya membutuhkan <strong>PHP</strong> dan <strong>Node.js (NPM)</strong> terinstall di komputermu.</p>
                
                <ol class="list-decimal list-inside space-y-5 text-slate-700">
                    <li class="pb-2 border-b border-slate-100">
                        <strong class="text-slate-800">Clone atau Download File Aplikasi</strong>
                        <p class="text-sm mt-1 mb-2 text-slate-500 ml-5">Buka terminal dan clone repository, lalu masuk ke foldernya.</p>
                        <pre class="bg-slate-900 text-slate-300 p-3 ml-5 rounded-xl text-sm overflow-x-auto"><code>git clone https://github.com/username/php-spa-framework.git
cd php-spa-framework</code></pre>
                    </li>
                    <li class="pb-2 border-b border-slate-100">
                        <strong class="text-slate-800">Install Dependencies Frontend</strong>
                        <p class="text-sm mt-1 mb-2 text-slate-500 ml-5">Perintah ini akan mendownload <code>tailwindcss</code> dan <code>bootstrap-icons</code> agar bisa digunakan offline tanpa koneksi internet.</p>
                        <pre class="bg-slate-900 text-slate-300 p-3 ml-5 rounded-xl text-sm overflow-x-auto"><code>npm install</code></pre>
                    </li>
                    <li>
                        <strong class="text-slate-800">Kompilasi CSS Pertama Kali</strong>
                        <p class="text-sm mt-1 mb-2 text-slate-500 ml-5">Jalankan proses kompilasi agar Tailwind meng-generate file <code>public/assets/css/tailwind.css</code> sesuai dengan layout HTML.</p>
                        <pre class="bg-slate-900 text-slate-300 p-3 ml-5 rounded-xl text-sm overflow-x-auto"><code>npm run css:build</code></pre>
                    </li>
                </ol>
            </div>
        </section>

        <!-- Section: Struktur Direktori -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="bi bi-folder2-open text-indigo-500"></i> Struktur Direktori
            </h2>
            <div class="bg-slate-900 rounded-xl p-5 overflow-x-auto text-sm text-slate-300 font-mono">
<pre>
<i class="bi bi-folder-fill text-indigo-400"></i> framework/
 ├── <i class="bi bi-folder-fill text-sky-400"></i> app/
 │    ├── <i class="bi bi-folder-fill text-sky-400"></i> Components/    <span class="text-slate-500"># Potongan UI reusable (navbar, footer)</span>
 │    ├── <i class="bi bi-folder-fill text-sky-400"></i> Pages/         <span class="text-slate-500"># Halaman utama aplikasi</span>
 │    └── <i class="bi bi-file-earmark-php text-indigo-300"></i> routes.php     <span class="text-slate-500"># Definisi semua rute/URL aplikasi</span>
 ├── <i class="bi bi-folder-fill text-sky-400"></i> core/             <span class="text-slate-500"># Logic mesin framework (Router, App, View)</span>
 ├── <i class="bi bi-folder-fill text-sky-400"></i> public/           <span class="text-slate-500"># Document root (hanya folder ini yang public)</span>
 │    ├── <i class="bi bi-folder-fill text-sky-400"></i> assets/        <span class="text-slate-500"># CSS, JS, Fonts</span>
 │    └── <i class="bi bi-file-earmark-php text-indigo-300"></i> index.php      <span class="text-slate-500"># Entry point utama</span>
 ├── <i class="bi bi-folder-fill text-sky-400"></i> src/              <span class="text-slate-500"># Source Tailwind CSS</span>
 └── <i class="bi bi-file-earmark-code text-indigo-300"></i> tailwind.config.js
</pre>
            </div>
        </section>

        <!-- Section: Cara Menjalankan -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="bi bi-play-circle-fill text-indigo-500"></i> Cara Menjalankan
            </h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-slate-600 mb-2 font-medium">1. Kompilasi Tailwind CSS (Buka Terminal 1)</p>
                    <pre class="bg-slate-900 text-slate-300 p-4 rounded-xl text-sm overflow-x-auto"><code><span class="text-slate-500"># Mode development (auto build saat ada file berubah)</span>
npm run css:watch

<span class="text-slate-500"># Mode production (minify)</span>
npm run css:build</code></pre>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-2 font-medium">2. Jalankan Server PHP (Buka Terminal 2)</p>
                    <pre class="bg-slate-900 text-slate-300 p-4 rounded-xl text-sm overflow-x-auto"><code><span class="text-slate-500"># Jalankan local server PHP ke folder public/</span>
php -S localhost:8080 -t public</code></pre>
                </div>
            </div>
        </section>

        <!-- Section: Routing -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="bi bi-signpost-split-fill text-indigo-500"></i> Routing & SPA Navigation
            </h2>
            <p class="text-slate-600 mb-4">Daftarkan URL di <code>app/routes.php</code>. Untuk membuat perpindahan halaman menjadi SPA tanpa reload, cukup tambahkan <code>data-spa-link</code> pada elemen <code>&lt;a&gt;</code>.</p>
            
            <pre class="bg-slate-900 text-slate-300 p-4 rounded-xl text-sm overflow-x-auto mb-4"><code><span class="text-slate-500">// app/routes.php</span>
<span class="text-indigo-400">use</span> <span class="text-sky-300">Core\View</span>;

$router->get(<span class="text-amber-300">'/'</span>, <span class="text-indigo-400">function</span> () {
    <span class="text-indigo-400">return</span> View::render(<span class="text-amber-300">'home'</span>);
});

$router->get(<span class="text-amber-300">'/tentang'</span>, <span class="text-indigo-400">function</span> () {
    <span class="text-indigo-400">return</span> View::render(<span class="text-amber-300">'about'</span>);
});</code></pre>
            
            <p class="text-sm font-medium text-slate-800 mb-2">Contoh Link HTML:</p>
            <pre class="bg-slate-900 text-slate-300 p-4 rounded-xl text-sm overflow-x-auto"><code>&lt;<span class="text-rose-400">a</span> <span class="text-sky-300">href</span>=<span class="text-amber-300">"/tentang"</span> <span class="text-sky-300">data-spa-link</span> <span class="text-sky-300">class</span>=<span class="text-amber-300">"text-indigo-500 hover:underline"</span>&gt;
    Ke Halaman Tentang
&lt;/<span class="text-rose-400">a</span>&gt;</code></pre>
        </section>

        <!-- Section: Middleware -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="bi bi-shield-lock-fill text-indigo-500"></i> Proteksi Middleware
            </h2>
            <p class="text-slate-600 mb-4">Framework ini sudah dilengkapi sistem <strong>Middleware</strong> bawaan di <code>Core\App</code> untuk mencegat *request* sebelum sampai ke rute (misalnya untuk mengecek status Login).</p>
            
            <p class="text-slate-600 mb-4">Buat class middleware di folder <code>app/Middleware/AuthMiddleware.php</code> (sudah dibuatkan contohnya) lalu panggil di <code>app/routes.php</code>:</p>
            
            <pre class="bg-slate-900 text-slate-300 p-4 rounded-xl text-sm overflow-x-auto mb-4"><code><span class="text-indigo-400">use</span> <span class="text-sky-300">App\Middleware\AuthMiddleware</span>;

<span class="text-slate-500">// Opsi 1: Menerapkan pada Group</span>
$router->group([<span class="text-amber-300">'prefix'</span> => <span class="text-amber-300">'/admin'</span>, <span class="text-amber-300">'middleware'</span> => [AuthMiddleware::class]], <span class="text-indigo-400">function</span> ($router) {
    $router->get(<span class="text-amber-300">'/dashboard'</span>, <span class="text-indigo-400">function</span>() {
        <span class="text-indigo-400">return</span> <span class="text-amber-300">"Halaman Rahasia"</span>;
    });
});

<span class="text-slate-500">// Opsi 2: Menerapkan langsung pada single Route</span>
$router->get(<span class="text-amber-300">'/profile'</span>, <span class="text-indigo-400">function</span>() {
    <span class="text-indigo-400">return</span> <span class="text-amber-300">"Profil"</span>;
})->middleware(AuthMiddleware::class);</code></pre>
        </section>

        <!-- Section: Views & Components -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="bi bi-file-earmark-code-fill text-indigo-500"></i> Views & Components
            </h2>
            <p class="text-slate-600 mb-4">View diletakkan di <code>app/Pages/</code>. Kamu bisa memanggil file komponen UI (seperti navbar) yang ada di <code>app/Components/</code> menggunakan <code>View::component()</code>.</p>
            
            <pre class="bg-slate-900 text-slate-300 p-4 rounded-xl text-sm overflow-x-auto"><code><span class="text-slate-500">&lt;!-- app/Pages/home.php --&gt;</span>
&lt;?php <span class="text-indigo-400">use</span> <span class="text-sky-300">Core\View</span>; ?&gt;

<span class="text-slate-500">&lt;!-- Set judul dinamis saat navigasi SPA --&gt;</span>
&lt;<span class="text-rose-400">div</span> <span class="text-sky-300">data-spa-title</span>=<span class="text-amber-300">"Halaman Home"</span>&gt;

    <span class="text-slate-500">&lt;!-- Panggil komponen navbar (app/Components/navbar.php) --&gt;</span>
    &lt;?= View::component(<span class="text-amber-300">'navbar'</span>) ?&gt;

    &lt;<span class="text-rose-400">main</span> <span class="text-sky-300">class</span>=<span class="text-amber-300">"p-4"</span>&gt;
        &lt;<span class="text-rose-400">h1</span>&gt;Hello World&lt;/<span class="text-rose-400">h1</span>&gt;
    &lt;/<span class="text-rose-400">main</span>&gt;

&lt;/<span class="text-rose-400">div</span>&gt;</code></pre>
        </section>

        <!-- Section: UI Components -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="bi bi-box-fill text-indigo-500"></i> UI Components & Javascript
            </h2>
            <p class="text-slate-600 mb-6">Framework ini dilengkapi dengan beberapa helper UI Vanilla Javascript yang siap pakai (berada di <code>public/assets/js/app.js</code>).</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Toast Notification -->
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                    <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2"><i class="bi bi-bell-fill text-amber-500"></i> Toast Notification</h3>
                    <p class="text-sm text-slate-600 mb-4">Gunakan fungsi global <code>showToast(pesan, tipe)</code>.</p>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="showToast('Berhasil menyimpan data!', 'success')" class="px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-lg hover:bg-emerald-200">Success</button>
                        <button onclick="showToast('Terjadi kesalahan!', 'error')" class="px-4 py-2 bg-rose-100 text-rose-700 text-sm font-medium rounded-lg hover:bg-rose-200">Error</button>
                        <button onclick="showToast('Informasi penting', 'info')" class="px-4 py-2 bg-sky-100 text-sky-700 text-sm font-medium rounded-lg hover:bg-sky-200">Info</button>
                    </div>
                </div>

                <!-- Global Loading -->
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                    <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2"><i class="bi bi-arrow-repeat text-indigo-500"></i> Global Loading Overlay</h3>
                    <p class="text-sm text-slate-600 mb-4">Cocok saat menunggu request API. Gunakan <code>SPA.showLoading()</code>.</p>
                    <button onclick="window.SPA.showLoading(); setTimeout(() => window.SPA.hideLoading(), 2000);" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                        <i class="bi bi-play-fill"></i> Tampilkan Loading (2 detik)
                    </button>
                </div>

                <!-- Tooltip -->
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                    <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2"><i class="bi bi-chat-square-text-fill text-sky-500"></i> Tooltip</h3>
                    <p class="text-sm text-slate-600 mb-4">Tambahkan atribut <code>data-tooltip="Pesan"</code> pada elemen apapun.</p>
                    <button data-tooltip="Halo! Ini tooltip dari framework" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-900">
                        Arahkan kursor ke sini
                    </button>
                    <p class="text-xs text-slate-400 mt-3">*Karena menggunakan absolute positioning murni dari JS, sangat ringan.</p>
                </div>

                <!-- Buttons & UI -->
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                    <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2"><i class="bi bi-input-cursor-text text-emerald-500"></i> Dropdowns & Modals</h3>
                    <p class="text-sm text-slate-600 mb-4">Mendukung *data attributes* bawaan untuk Modal dan Dropdown layaknya Bootstrap, namun dibangun murni dengan Vanilla JS.</p>
                    <pre class="bg-slate-900 text-slate-300 p-3 rounded-lg text-xs overflow-x-auto"><code>&lt;button data-modal-open="#my-modal"&gt;Buka&lt;/button&gt;
&lt;button data-dropdown="#my-menu"&gt;Menu&lt;/button&gt;</code></pre>
                </div>
            </div>
        </section>

        <!-- Section: API Service -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="bi bi-cloud-arrow-down-fill text-indigo-500"></i> Fetching Data API
            </h2>
            <p class="text-slate-600 mb-4">Karena ini framework SPA, dianjurkan memuat data dari API luar menggunakan JavaScript Helper <code>Api</code> yang sudah dibangun di atas Fetch API modern.</p>
            
            <div class="mb-6">
                <button onclick="fetchDemoApi()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-sm transition-colors duration-200 flex items-center gap-2 text-sm">
                    <i class="bi bi-hdd-network"></i>
                    Test Fetch Data Dummy
                </button>
            </div>
            
            <div id="demo-api-result" class="hidden bg-slate-50 border border-slate-200 rounded-lg p-5 mb-6 text-sm font-mono text-slate-800 overflow-x-auto shadow-inner"></div>

            <pre class="bg-slate-900 text-slate-300 p-4 rounded-xl text-sm overflow-x-auto"><code><span class="text-slate-500">// Setup Base URL API</span>
Api.setBaseUrl(<span class="text-amber-300">'https://api-dummy.com/api'</span>);

<span class="text-slate-500">// GET Data</span>
<span class="text-indigo-400">const</span> response = <span class="text-indigo-400">await</span> Api.get(<span class="text-amber-300">'/users'</span>);

<span class="text-slate-500">// POST Data</span>
<span class="text-indigo-400">const</span> create = <span class="text-indigo-400">await</span> Api.post(<span class="text-amber-300">'/users'</span>, { name: <span class="text-amber-300">'John Doe'</span> });

<span class="text-indigo-400">if</span> (response.success) {
    console.log(response.data);
    showToast(<span class="text-amber-300">'Data berhasil dimuat'</span>, <span class="text-amber-300">'success'</span>);
}</code></pre>
        </section>

    </div>
</main>

<?= View::component('footer') ?>

<script>
// Animasi fade-in sederhana
document.addEventListener('spa:contentLoaded', () => {
    document.querySelectorAll('.animate-fade-in-up').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(10px)';
        el.style.transition = `all 0.5s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 50);
    });
});

async function fetchDemoApi() {
    const resultBox = document.getElementById('demo-api-result');
    resultBox.classList.remove('hidden');
    resultBox.innerHTML = '<span class="text-slate-500 flex items-center gap-2"><i class="bi bi-hourglass-split animate-spin"></i> Memuat data dari jsonplaceholder...</span>';
    
    // Fetch dari public API
    const result = await window.Api.get('/users/1');
    
    if (result.success) {
        resultBox.innerHTML = JSON.stringify(result.data, null, 2).replace(/\n/g, '<br>').replace(/ /g, '&nbsp;');
        if (typeof showToast === 'function') showToast('Data API berhasil dimuat!', 'success');
    } else {
        resultBox.innerHTML = '<span class="text-rose-500 flex items-center gap-2"><i class="bi bi-exclamation-triangle"></i> Gagal mengambil data.</span>';
        if (typeof showToast === 'function') showToast('Gagal memuat API', 'error');
    }
}
</script>
</div>
