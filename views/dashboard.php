<?php
/**
 * @var int $totalItems
 * @var int $todayTransactions
 * @var int $totalMasukMonth
 * @var int $totalKeluarMonth
 * @var array $lowStockItems
 * @var array $recentTransactions
 * @var string $baseUrl
 */
?>
<!-- Ringkasan Statistik -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jenis Barang</p>
            <h3 class="text-xl font-bold text-slate-900 mt-1"><?= formatNumber($totalItems) ?></h3>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stok Menipis</p>
            <h3 class="text-xl font-bold text-slate-900 mt-1"><?= count($lowStockItems) ?></h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" /></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Masuk (Bulan Ini)</p>
            <h3 class="text-xl font-bold text-slate-900 mt-1"><?= formatNumber($totalMasukMonth) ?></h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keluar (Bulan Ini)</p>
            <h3 class="text-xl font-bold text-slate-900 mt-1"><?= formatNumber($totalKeluarMonth) ?></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
    <!-- 10 Transaksi Terakhir -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">Transaksi Terakhir</h3>
            <a href="<?= e($baseUrl) ?>/reports" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-widest">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Barang</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Jumlah</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($recentTransactions as $tx): ?>
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-slate-900"><?= e($tx['nama']) ?></div>
                            <div class="text-[9px] text-slate-400 uppercase font-bold tracking-tight"><?= str_replace('_', ' ', $tx['tipe']) ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-right font-bold <?= $tx['arah'] > 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                            <?= $tx['arah'] > 0 ? '+' : '-' ?><?= formatNumber($tx['jumlah']) ?>
                        </td>
                        <td class="px-6 py-4 text-[10px] text-slate-400 font-medium"><?= date('d/m H:i', strtotime($tx['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Barang Menipis -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">Stok Kritis</h3>
        </div>
        <div class="p-4 space-y-2">
            <?php if (empty($lowStockItems)): ?>
                <div class="py-12 text-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stok Aman Terkendali</p>
                </div>
            <?php else: ?>
                <?php foreach ($lowStockItems as $item): ?>
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between group hover:border-rose-200 hover:bg-rose-50/30 transition-all">
                    <div>
                        <div class="text-sm font-bold text-slate-900"><?= e($item['nama']) ?></div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">
                            Saldo: <span class="text-rose-600"><?= formatNumber($item['saldo']) ?></span> 
                            <span class="mx-1 text-slate-300">/</span> 
                            Min: <?= formatNumber($item['stok_minimum']) ?>
                        </div>
                    </div>
                    <a href="<?= e($baseUrl) ?>/stock/in?item_id=<?= $item['id'] ?? '' ?>" class="p-2 bg-white text-slate-400 rounded-lg border border-slate-200 shadow-sm opacity-0 group-hover:opacity-100 transition-all hover:text-emerald-600 hover:border-emerald-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
