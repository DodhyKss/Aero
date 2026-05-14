<?php
/** Navbar Component */
$currentUri = $_SERVER['REQUEST_URI'] ?? '/';
?>
<nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="/" class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-sky-500 bg-clip-text text-transparent flex items-center gap-2" data-spa-link>
            <i class="bi bi-rocket-takeoff-fill text-indigo-500"></i> Aero
        </a>
        <ul class="flex gap-2">
            <li>
                <a href="/" 
                   class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors duration-200 <?= $currentUri === '/' ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' ?>"
                   data-spa-link>
                    <i class="bi bi-journal-text"></i> Dokumentasi
                </a>
            </li>
            <li>
                <a href="https://github.com" target="_blank" class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-colors duration-200">
                    <i class="bi bi-github"></i> GitHub
                </a>
            </li>
        </ul>
    </div>
</nav>
