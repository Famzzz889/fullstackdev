<!-- Ringkasan Statistik -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Barang</p>
            <h3 class="text-2xl font-bold text-slate-900"><?= formatNumber($totalItems) ?></h3>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transaksi Hari Ini</p>
            <h3 class="text-2xl font-bold text-slate-900"><?= formatNumber($todayTransactions) ?></h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stok Menipis</p>
            <h3 class="text-2xl font-bold text-slate-900"><?= count($lowStockItems) ?></h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Update Terakhir</p>
            <h3 class="text-sm font-bold text-slate-900"><?= !empty($recentTransactions) ? formatDate($recentTransactions[0]['tanggal']) : '-' ?></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Peringatan Stok (Tabel Modern) -->
    <div class="lg:col-span-2 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Peringatan Stok Rendah</h3>
            <a href="<?= e($baseUrl) ?>/items" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Lihat Semua &rarr;</a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Barang</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Saldo Saat Ini</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Batas Minimum</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($lowStockItems)): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p class="text-sm">Semua stok barang dalam kondisi aman.</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($lowStockItems as $item): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900"><?= e($item['nama']) ?></td>
                            <td class="px-6 py-4 text-sm font-bold text-rose-600 text-right"><?= formatNumber($item['saldo']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-400 text-right font-medium"><?= formatNumber($item['stok_minimum']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Aktivitas Terbaru (Timeline) -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-900">Aktivitas Terbaru</h3>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    <?php foreach ($recentTransactions as $idx => $tx): ?>
                    <li>
                        <div class="relative pb-8">
                            <?php if ($idx !== count($recentTransactions) - 1): ?>
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-100" aria-hidden="true"></span>
                            <?php endif; ?>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-xl flex items-center justify-center ring-8 ring-white <?= $tx['arah'] == 1 ? 'bg-sky-50 text-sky-600' : 'bg-orange-50 text-orange-600' ?>">
                                        <?php if ($tx['arah'] == 1): ?>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        <?php else: ?>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-slate-900 font-semibold"><?= e($tx['nama']) ?></p>
                                        <p class="text-xs text-slate-500 mt-0.5"><?= formatNumber($tx['jumlah']) ?> <?= e($tx['tipe']) ?></p>
                                    </div>
                                    <div class="text-right text-[10px] whitespace-nowrap text-slate-400 font-bold uppercase tracking-tighter">
                                        <?= formatDate($tx['tanggal']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="<?= e($baseUrl) ?>/reports" class="block w-full text-center mt-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors uppercase tracking-widest">Laporan Lengkap</a>
        </div>
    </div>
</div>
