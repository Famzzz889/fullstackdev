<?php
/**
 * @var string $baseUrl
 * @var Csrf $Csrf
 */
?>
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center space-x-4">
        <a href="<?= e($baseUrl) ?>/items" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Barang Baru</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form action="<?= e($baseUrl) ?>/items/create" method="POST" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
            <?= Csrf::csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kode" class="block text-sm font-semibold text-slate-700 mb-2">Kode Barang</label>
                    <input type="text" name="kode" id="kode" required value="<?= e(old('kode')) ?>"
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                        placeholder="misal: PLP-001">
                    <p class="mt-1.5 text-xs text-slate-400">Kode harus unik dan tidak boleh sama dengan barang lain.</p>
                </div>

                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang</label>
                    <input type="text" name="nama" id="nama" required value="<?= e(old('nama')) ?>"
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                        placeholder="misal: Pulpen Pilot G2 Hitam">
                </div>

                <div>
                    <label for="satuan" class="block text-sm font-semibold text-slate-700 mb-2">Satuan</label>
                    <input type="text" name="satuan" id="satuan" required placeholder="misal: Pcs, Rim, Box" value="<?= e(old('satuan')) ?>"
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-slate-400">
                </div>

                <div>
                    <label for="stok_minimum" class="block text-sm font-semibold text-slate-700 mb-2">Stok Minimum</label>
                    <input type="number" name="stok_minimum" id="stok_minimum" required min="0" value="<?= (int)old('stok_minimum', 0) ?>"
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-slate-400">
                    <p class="mt-1.5 text-xs text-slate-400">Batas untuk peringatan stok rendah.</p>
                </div>
            </div>

            <div class="pt-6 flex justify-end space-x-3 border-t border-slate-100">
                <a href="<?= e($baseUrl) ?>/items" class="inline-flex justify-center py-2.5 px-6 border border-slate-300 shadow-sm text-sm font-semibold rounded-xl text-slate-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    Batal
                </a>
                <button type="submit" :disabled="loading"
                    class="inline-flex justify-center py-2.5 px-8 border border-transparent shadow-sm text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-60 transition-all">
                    <span x-show="!loading">Simpan Barang</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
