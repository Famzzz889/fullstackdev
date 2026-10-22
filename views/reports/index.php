<div class="bg-white shadow sm:rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Filter Laporan</h3>
        <form action="<?= e($baseUrl) ?>/reports" method="GET" class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="item_id" class="block text-sm font-medium text-gray-700">Barang</label>
                <select name="item_id" id="item_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Semua Barang</option>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= $item['id'] ?>" <?= $itemId == $item['id'] ? 'selected' : '' ?>>
                            <?= e($item['kode']) ?> - <?= e($item['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe</label>
                <select name="tipe" id="tipe" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Semua Tipe</option>
                    <option value="masuk" <?= $tipe == 'masuk' ? 'selected' : '' ?>>Masuk</option>
                    <option value="keluar" <?= $tipe == 'keluar' ? 'selected' : '' ?>>Keluar</option>
                    <option value="koreksi_masuk" <?= $tipe == 'koreksi_masuk' ? 'selected' : '' ?>>Koreksi Masuk</option>
                    <option value="koreksi_keluar" <?= $tipe == 'koreksi_keluar' ? 'selected' : '' ?>>Koreksi Keluar</option>
                    <option value="penyesuaian_opname" <?= $tipe == 'penyesuaian_opname' ? 'selected' : '' ?>>Opname</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pihak</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-sm text-gray-500 text-center">Tidak ada data transaksi</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $tx): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= formatDate($tx['tanggal']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= e($tx['kode']) ?> - <?= e($tx['nama']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?= $tx['tipe'] == 'masuk' ? 'bg-green-100 text-green-800' : '' ?>
                                        <?= $tx['tipe'] == 'keluar' ? 'bg-red-100 text-red-800' : '' ?>
                                        <?= strpos($tx['tipe'], 'koreksi') !== false ? 'bg-yellow-100 text-yellow-800' : '' ?>
                                        <?= $tx['tipe'] == 'penyesuaian_opname' ? 'bg-blue-100 text-blue-800' : '' ?>
                                    ">
                                        <?= e(str_replace('_', ' ', ucfirst($tx['tipe']))) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold <?= $tx['arah'] == 1 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $tx['arah'] == 1 ? '+' : '-' ?><?= formatNumber($tx['jumlah']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= e($tx['pihak']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= e($tx['keterangan']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= e($tx['nama_user']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
