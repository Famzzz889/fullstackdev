<?php
/**
 * @var string $baseUrl
 * @var array $results
 */
?>
<div class="space-y-6">
    <div class="flex items-center space-x-4">
        <a href="<?= e($baseUrl) ?>/reports" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Cek Konsistensi Data</h1>
            <p class="text-sm text-slate-500 mt-1">Membandingkan saldo snapshot dengan akumulasi riwayat transaksi.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Barang</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Saldo Snapshot</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Saldo Ledger (SUM)</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Selisih</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($results as $row): ?>
                        <?php $diff = $row['saldo_sistem'] - $row['saldo_ledger']; ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                <div class="font-bold"><?= e($row['nama']) ?></div>
                                <div class="text-[10px] text-slate-400"><?= e($row['kode']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-medium text-slate-600"><?= formatNumber($row['saldo_sistem']) ?></td>
                            <td class="px-6 py-4 text-sm text-right font-medium text-slate-600"><?= formatNumber($row['saldo_ledger']) ?></td>
                            <td class="px-6 py-4 text-sm text-right font-bold <?= $diff != 0 ? 'text-rose-600' : 'text-slate-400' ?>">
                                <?= $diff > 0 ? '+' : '' ?><?= formatNumber($diff) ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($diff == 0): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase">Konsisten</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100 uppercase">Selisih</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
