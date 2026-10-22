<?php
/**
 * @var string $baseUrl
 * @var array $item
 * @var array $history
 */
?>
<div class="space-y-6">
    <div class="flex items-center space-x-4">
        <a href="<?= e($baseUrl) ?>/items" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Riwayat Barang</h1>
            <p class="text-sm text-slate-500 mt-1"><?= e($item['kode']) ?> - <?= e($item['nama']) ?></p>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end space-x-3 no-print">
        <a href="<?= e($baseUrl) ?>/items/opname?id=<?= $item['id'] ?>" class="inline-flex items-center justify-center bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl px-4 py-2 text-sm font-bold transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
            Stock Opname
        </a>
        <a href="<?= e($baseUrl) ?>/reports?item_id=<?= $item['id'] ?>" class="inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-4 py-2 text-sm font-bold transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Kartu Stok
        </a>
    </div>

    <!-- Statistik Singkat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Saldo Saat Ini</p>
            <h3 class="text-2xl font-bold text-slate-900"><?= formatNumber($item['saldo']) ?> <span class="text-sm font-medium text-slate-400"><?= e($item['satuan']) ?></span></h3>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Stok Minimum</p>
            <h3 class="text-2xl font-bold text-slate-900"><?= formatNumber($item['stok_minimum']) ?> <span class="text-sm font-medium text-slate-400"><?= e($item['satuan']) ?></span></h3>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
            <?php
            $statusClass = 'text-emerald-600 bg-emerald-50';
            $statusText = 'Aman';
            if ($item['saldo'] < $item['stok_minimum']) {
                $statusClass = 'text-rose-600 bg-rose-50';
                $statusText = 'Stok Menipis';
            } elseif ($item['saldo'] <= 1.5 * $item['stok_minimum']) {
                $statusClass = 'text-amber-600 bg-amber-50';
                $statusText = 'Mendekati Minimum';
            }
            ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $statusClass ?> mt-1">
                <?= $statusText ?>
            </span>
        </div>
    </div>

    <!-- Table Riwayat -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ 
        correctingId: null,
        correctingKey: '<?= generateIdempotencyKey() ?>',
        showModal: false,
        openKoreksi(id) {
            this.correctingId = id;
            this.showModal = true;
        }
    }">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tipe</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pihak/Tujuan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pencatat</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($history as $tx): ?>
                        <?php 
                        $isCorrection = in_array($tx['tipe'], ['koreksi_masuk', 'koreksi_keluar']);
                        $isCorrected = false; // logic for isCorrected can be added via ref_transaction_id search
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                                <?= formatDate($tx['tanggal']) ?>
                                <div class="text-[10px] text-slate-400 mt-0.5"><?= date('H:i', strtotime($tx['created_at'])) ?> WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $tipeClass = 'bg-slate-100 text-slate-600';
                                if ($tx['tipe'] == 'masuk') $tipeClass = 'bg-sky-50 text-sky-700';
                                if ($tx['tipe'] == 'keluar') $tipeClass = 'bg-orange-50 text-orange-700';
                                if ($isCorrection) $tipeClass = 'bg-purple-50 text-purple-700';
                                ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border border-transparent <?= $tipeClass ?>">
                                    <?= str_replace('_', ' ', $tx['tipe']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold <?= $tx['arah'] > 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                                <?= ($tx['arah'] > 0 ? '+' : '-') . formatNumber($tx['jumlah']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600"><?= e($tx['pihak'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate" title="<?= e($tx['keterangan']) ?>">
                                <?= e($tx['keterangan'] ?? '-') ?>
                                <?php if ($tx['ref_transaction_id']): ?>
                                    <div class="text-[10px] text-indigo-500 font-bold mt-1">Ref ID: #<?= $tx['ref_transaction_id'] ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600"><?= e($tx['nama_user']) ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php if (!$isCorrection && !$tx['ref_transaction_id']): ?>
                                    <button @click="openKoreksi(<?= $tx['id'] ?>)" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider">Koreksi</button>
                                <?php else: ?>
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Koreksi -->
        <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="showModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block w-full max-w-lg p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl sm:align-middle">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Koreksi Transaksi</h3>
                    <p class="text-sm text-slate-500 mb-6">Koreksi akan membuat transaksi baru untuk membatalkan transaksi ini. Stok akan disesuaikan secara otomatis.</p>
                    
                    <form action="<?= e($baseUrl) ?>/stock/correct" method="POST" class="space-y-4">
                        <?= Csrf::csrf_field() ?>
                        <input type="hidden" name="ref_transaction_id" :value="correctingId">
                        <input type="hidden" name="idempotency_key" :value="correctingKey">
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Alasan Koreksi <span class="text-rose-500">*</span></label>
                            <textarea name="keterangan" required rows="3" class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Jelaskan alasan koreksi (misal: Salah input jumlah, Barang rusak saat diterima, dll)"></textarea>
                        </div>

                        <div class="pt-4 flex justify-end space-x-3">
                            <button type="button" @click="showModal = false" class="px-6 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-xl transition-all">Batal</button>
                            <button type="submit" class="px-8 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-200 transition-all">Simpan Koreksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
