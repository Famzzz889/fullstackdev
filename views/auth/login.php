<?php
/**
 * @var string $baseUrl
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gudang ATK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] h-screen overflow-hidden">
    <div class="flex h-full">
        <!-- Kolom Kiri: Visual/Marketing -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 to-violet-700 p-12 flex-col justify-between text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-12">
                    <div class="bg-white/20 p-2 rounded-xl backdrop-blur-md">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight">Gudang ATK</span>
                </div>
                
                <h1 class="text-5xl font-extrabold leading-tight mb-8">
                    Kelola Inventaris <br/> 
                    <span class="text-indigo-200">Lebih Akurat & Cepat.</span>
                </h1>
                
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="bg-white/10 p-1.5 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Anti-Double Transaction</h3>
                            <p class="text-indigo-100 text-sm opacity-80">Sistem cerdas mencegah input ganda saat jaringan tidak stabil.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-white/10 p-1.5 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Ledger Append-Only</h3>
                            <p class="text-indigo-100 text-sm opacity-80">Setiap perubahan stok tercatat rapi dan tidak bisa dihapus.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-white/10 p-1.5 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Real-time Stock Balance</h3>
                            <p class="text-indigo-100 text-sm opacity-80">Pantau saldo stok secara akurat di level database.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative z-10 text-sm text-indigo-200 opacity-60">
                &copy; 2026 Gudang ATK System. Dibuat untuk Technical Test.
            </div>
            
            <!-- Dekorasi background -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-indigo-900/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Kolom Kanan: Form Login -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 sm:p-12 md:p-24 bg-white relative">
            <div class="max-w-md w-full mx-auto">
                <div class="lg:hidden flex items-center space-x-3 mb-12">
                    <div class="bg-indigo-600 p-2 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-slate-900">Gudang ATK</span>
                </div>

                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Selamat Datang Kembali</h2>
                    <p class="text-slate-500">Silakan masuk untuk mengelola stok barang.</p>
                </div>

                <?php if ($flash = Flash::get('login_error')): ?>
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center space-x-3 text-rose-700">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm font-medium"><?= e($flash['message']) ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= e($baseUrl) ?>/login" method="POST" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
                    <?= Csrf::csrf_field() ?>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                        <input type="email" id="email" name="email" required autofocus
                            class="w-full rounded-xl border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-slate-400" 
                            placeholder="nama@atk.test">
                        <p class="mt-1.5 text-xs text-slate-400">Gunakan email yang sudah terdaftar.</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Kata Sandi</label>
                        <input type="password" id="password" name="password" required
                            class="w-full rounded-xl border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                            placeholder="••••••••">
                    </div>

                    <button type="submit" :disabled="loading"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white rounded-xl px-4 py-3.5 font-semibold shadow-sm shadow-indigo-200 transition-all flex items-center justify-center space-x-2">
                        <span x-show="!loading">Masuk ke Sistem</span>
                        <template x-if="loading">
                            <div class="flex items-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memproses...</span>
                            </div>
                        </template>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
