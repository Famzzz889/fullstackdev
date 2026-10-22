<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gudang ATK' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A]" x-data="{ sidebarOpen: false }">
    <!-- Toast Notifications -->
    <div class="fixed top-6 right-6 z-[100] space-y-3">
        <?php if ($flash = Flash::get('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform translate-x-8"
            class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 shadow-lg shadow-emerald-100/50 flex items-center space-x-3 min-w-[320px]">
            <div class="bg-emerald-500 p-1.5 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-emerald-900">Berhasil</p>
                <p class="text-xs text-emerald-700 opacity-80"><?= e($flash['message']) ?></p>
            </div>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <?php endif; ?>

        <?php if ($flash = Flash::get('error')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform translate-x-8"
            class="bg-rose-50 border border-rose-100 rounded-2xl p-4 shadow-lg shadow-rose-100/50 flex items-center space-x-3 min-w-[320px]">
            <div class="bg-rose-500 p-1.5 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-rose-900">Gagal</p>
                <p class="text-xs text-rose-700 opacity-80"><?= e($flash['message']) ?></p>
            </div>
            <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <div class="flex min-h-screen overflow-hidden">
        <!-- Sidebar (Desktop) -->
        <aside class="hidden lg:flex flex-col w-[260px] bg-white border-r border-[#E2E8F0] sticky top-0 h-screen">
            <div class="p-6 flex items-center space-x-3 border-b border-slate-50">
                <div class="bg-indigo-600 p-1.5 rounded-lg text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900 uppercase">Gudang ATK</span>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="<?= e($baseUrl) ?>/dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all <?= trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') == 'dashboard' ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-slate-50' ?>">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span>Dashboard</span>
                </a>
                <a href="<?= e($baseUrl) ?>/items" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all <?= strpos($_SERVER['REQUEST_URI'], '/items') !== false ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-slate-50' ?>">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span>Daftar Barang</span>
                </a>
                <div class="pt-4 pb-2 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Transaksi</div>
                <a href="<?= e($baseUrl) ?>/stock/in" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Stok Masuk</span>
                </a>
                <a href="<?= e($baseUrl) ?>/stock/out" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                    <span>Stok Keluar</span>
                </a>
                <a href="<?= e($baseUrl) ?>/reports" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span>Laporan Ledger</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-slate-50">
                <div class="flex items-center space-x-3 p-2 bg-slate-50 rounded-2xl">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold uppercase">
                        <?= substr(Auth::user()['nama'], 0, 2) ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate"><?= e(Auth::user()['nama']) ?></p>
                        <p class="text-[10px] text-slate-500 truncate"><?= e(Auth::user()['email']) ?></p>
                    </div>
                    <a href="<?= e($baseUrl) ?>/logout" class="text-rose-500 hover:text-rose-700 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Mobile Drawer -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="sidebarOpen = false"></div>
            <aside class="fixed inset-y-0 left-0 w-[260px] bg-white shadow-2xl flex flex-col"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                <!-- Sidebar content same as desktop... -->
                <div class="p-6 flex items-center space-x-3 border-b border-slate-50">
                    <div class="bg-indigo-600 p-1.5 rounded-lg text-white">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900 uppercase">Gudang ATK</span>
                </div>
                <!-- ... other nav items ... -->
            </aside>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-[#E2E8F0] flex items-center justify-between px-6 shrink-0 sticky top-0 z-40">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:bg-slate-50 rounded-xl">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <h2 class="text-lg font-bold text-slate-900"><?= $title ?? 'Dashboard' ?></h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-xs text-slate-400 font-medium hidden sm:block"><?= formatDate(date('Y-m-d')) ?></span>
                    <div class="h-8 w-px bg-slate-100 hidden sm:block"></div>
                    <div class="flex items-center space-x-2 bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-full text-xs font-bold uppercase">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                        </span>
                        <span>Sistem Aktif</span>
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8">
                <?= $content ?>
            </main>
        </div>
    </div>
</body>
</html>
