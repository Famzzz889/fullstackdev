<?php
/**
 * @var string $baseUrl
 * @var array $items
 * @var string $search
 * @var int $page
 * @var int $totalPages
 */
?>
<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Daftar Barang</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data master barang dan batas stok minimum.</p>
        </div>
        <a href="<?= e($baseUrl) ?>/items/create" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-4 py-2.5 font-medium shadow-sm shadow-indigo-200 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Barang
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
        <form action="<?= e($baseUrl) ?>/items" method="GET" class="flex flex-col md:flex-row items-center gap-4">
            <div class="flex-1 w-full relative">
                <input type="text" name="q" value="<?= e($search) ?>" 
                    class="w-full rounded-xl border-slate-300 pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                    placeholder="Cari kode atau nama barang...">
                <div class="absolute left-4 top-3 text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <label class="flex items-center space-x-2 cursor-pointer group">
                    <input type="checkbox" name="low_stock" value="1" <?= isset($_GET['low_stock']) && $_GET['low_stock'] == '1' ? 'checked' : '' ?>
                        class="w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                    <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Hanya stok menipis</span>
                </label>
            </div>

            <div class="flex items-center space-x-3 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-6 py-2.5 font-semibold transition-all">
                    Cari
                </button>
                <?php if ($search || isset($_GET['low_stock'])): ?>
                    <a href="<?= e($baseUrl) ?>/items" class="text-slate-500 hover:text-slate-700 font-medium px-2">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Barang</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Satuan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Saldo</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Min. Stok</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-4">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                </div>
                                <h3 class="text-slate-900 font-bold">Barang Tidak Ditemukan</h3>
                                <p class="text-slate-500 text-sm mt-1">Belum ada data barang atau coba kata kunci lain.</p>
                                <a href="<?= e($baseUrl) ?>/items/create" class="mt-4 text-indigo-600 font-bold text-sm hover:underline">+ Tambah Barang Baru</a>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 text-sm font-medium text-slate-500"><?= e($item['kode']) ?></td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-900"><?= e($item['nama']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-500"><?= e($item['satuan']) ?></td>
                            <td class="px-6 py-4 text-sm text-right">
                                <?php
                                $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                $statusText = 'Aman';
                                
                                if ($item['saldo'] < $item['stok_minimum']) {
                                    $statusClass = 'bg-rose-50 text-rose-700 border-rose-100';
                                    $statusText = 'Stok Menipis';
                                } elseif ($item['saldo'] <= 1.5 * $item['stok_minimum']) {
                                    $statusClass = 'bg-amber-50 text-amber-700 border-amber-100';
                                    $statusText = 'Mendekati Minimum';
                                }
                                ?>
                                <div class="flex flex-col items-end">
                                    <span class="font-bold <?= $item['saldo'] < $item['stok_minimum'] ? 'text-rose-600' : 'text-slate-900' ?>">
                                        <?= formatNumber($item['saldo']) ?>
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border <?= $statusClass ?> mt-1">
                                        <?= $statusText ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400 text-right font-medium"><?= formatNumber($item['stok_minimum']) ?></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="<?= e($baseUrl) ?>/items/history?id=<?= $item['id'] ?>" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Riwayat">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </a>
                                    <a href="<?= e($baseUrl) ?>/items/edit?id=<?= $item['id'] ?>" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </a>
                                    <form action="<?= e($baseUrl) ?>/items/delete" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                        <?= Csrf::csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Halaman <?= $page ?> dari <?= $totalPages ?></p>
            <div class="flex space-x-2">
                <?php if ($page > 1): ?>
                    <a href="<?= e($baseUrl) ?>/items?page=<?= $page - 1 ?>&q=<?= e($search) ?>" class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">Sebelumnya</a>
                <?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="<?= e($baseUrl) ?>/items?page=<?= $page + 1 ?>&q=<?= e($search) ?>" class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">Berikutnya</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
