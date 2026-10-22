<?php
/**
 * @var string $baseUrl
 * @var array $item
 * @var string $month
 * @var int $saldoAwal
 * @var array $mutasi
 * @var int $totalMasuk
 * @var int $totalKeluar
 * @var int $saldoAkhir
 */
?>
<div class="space-y-6">
    <div class="flex items-center justify-between no-print">
        <div class="flex items-center space-x-4">
            <a href="<?= e($baseUrl) ?>/reports" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Kartu Stok</h1>
                <p class="text-sm text-slate-500 mt-1"><?= e($item['kode']) ?> - <?= e($item['nama']) ?> | <?= date('F Y', strtotime($month . '-01')) ?></p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?= $_SERVER['REQUEST_URI'] ?>&export=excel" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl px-6 py-2.5 font-semibold transition-all flex items-center shadow-lg shadow-emerald-100">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Export Excel
            </a>
            <button onclick="window.print()" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl px-6 py-2.5 font-semibold transition-all flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak
            </button>
        </div>
    </div>

    <!-- Ringkasan Kartu Stok -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Saldo Awal</p>
            <h3 class="text-xl font-bold text-slate-900"><?= formatNumber($saldoAwal) ?></h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-1">Total Masuk</p>
            <h3 class="text-xl font-bold text-emerald-700"><?= formatNumber($totalMasuk) ?></h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-[10px] font-bold text-rose-600 uppercase tracking-widest mb-1">Total Keluar</p>
            <h3 class="text-xl font-bold text-rose-700"><?= formatNumber($totalKeluar) ?></h3>
        </div>
        <div class="bg-indigo-600 rounded-xl shadow-lg shadow-indigo-600/20 p-6 text-white">
            <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest mb-1">Saldo Akhir</p>
            <h3 class="text-xl font-bold"><?= formatNumber($saldoAkhir) ?></h3>
        </div>
    </div>

    <!-- Tabel Mutasi -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Masuk</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Keluar</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="bg-slate-50/50">
                        <td class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest" colspan="2">SALDO AWAL</td>
                        <td class="px-6 py-3 text-right text-sm text-slate-400">-</td>
                        <td class="px-6 py-3 text-right text-sm text-slate-400">-</td>
                        <td class="px-6 py-3 text-right text-sm font-bold text-slate-600"><?= formatNumber($saldoAwal) ?></td>
                    </tr>
                    <?php if (empty($mutasi)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-slate-400 text-sm italic">Tidak ada mutasi pada bulan ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mutasi as $tx): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap"><?= formatDate($tx['tanggal']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                <div class="font-bold text-slate-800"><?= e($tx['pihak'] ?? 'Internal') ?></div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tight mt-0.5"><?= e($tx['keterangan']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-emerald-600">
                                <?= $tx['arah'] > 0 ? formatNumber($tx['jumlah']) : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-rose-600">
                                <?= $tx['arah'] < 0 ? formatNumber($tx['jumlah']) : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-slate-900">
                                <?= formatNumber($tx['saldo_berjalan']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white; padding: 0; }
        .rounded-2xl { border-radius: 0; }
        .shadow-sm { box-shadow: none; }
        .border { border: 1px solid #e2e8f0; }
    }
</style>
