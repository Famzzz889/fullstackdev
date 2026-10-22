<div class="max-w-2xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200" x-data="{ submitting: false }">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Catat Stok Masuk</h3>
    </div>
    <form action="<?= e($baseUrl) ?>/stock/in" method="POST" class="p-6 space-y-6" @submit="submitting = true">
        <?= Csrf::input() ?>
        <input type="hidden" name="idempotency_key" value="<?= e($idempotencyKey) ?>">
        
        <div>
            <label for="item_id" class="block text-sm font-medium text-gray-700">Pilih Barang</label>
            <select name="item_id" id="item_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?= $item['id'] ?>" <?= $selectedItemId == $item['id'] ? 'selected' : '' ?>>
                        <?= e($item['kode']) ?> - <?= e($item['nama']) ?> (<?= e($item['satuan']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Masuk</label>
                <input type="number" name="jumlah" id="jumlah" required min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                <input type="date" name="tanggal" id="tanggal" required value="<?= date('Y-m-d') ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>

        <div>
            <label for="pihak" class="block text-sm font-medium text-gray-700">Pihak (Supplier/Pengirim)</label>
            <input type="text" name="pihak" id="pihak" placeholder="Contoh: PT Stationery Jaya" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>

        <div>
            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="3" placeholder="Tambahkan catatan jika perlu" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="<?= e($baseUrl) ?>/dashboard" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Batal
            </a>
            <button type="submit" :disabled="submitting" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!submitting">Simpan Transaksi</span>
                <span x-show="submitting" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>
    </form>
</div>
