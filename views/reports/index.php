<?php
/**
 * @var string $baseUrl
 * @var array $items
 */
?>
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Laporan Kartu Stok</h1>
        <p class="text-sm text-slate-500 mt-1">Pilih barang dan bulan untuk melihat mutasi stok detail.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form action="<?= e($baseUrl) ?>/reports" method="GET" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="item_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Barang</label>
                    <select name="item_id" id="item_id" required 
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>">
                                <?= e($item['kode']) ?> - <?= e($item['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="month" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Bulan</label>
                    <input type="month" name="month" id="month" required value="<?= date('Y-m') ?>"
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="<?= e($baseUrl) ?>/reports/consistency" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Cek Konsistensi Data
                </a>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-8 py-2.5 font-semibold transition-all">
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
