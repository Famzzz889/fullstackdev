<div class="max-w-2xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Barang Baru</h3>
    </div>
    <form action="<?= e($baseUrl) ?>/items/create" method="POST" class="p-6 space-y-6">
        <?= Csrf::input() ?>
        
        <div>
            <label for="kode" class="block text-sm font-medium text-gray-700">Kode Barang</label>
            <input type="text" name="kode" id="kode" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>

        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Barang</label>
            <input type="text" name="nama" id="nama" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                <input type="text" name="satuan" id="satuan" required placeholder="misal: Pcs, Rim, Box" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="stok_minimum" class="block text-sm font-medium text-gray-700">Stok Minimum</label>
                <input type="number" name="stok_minimum" id="stok_minimum" required min="0" value="0" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="<?= e($baseUrl) ?>/items" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Batal
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Simpan
            </button>
        </div>
    </form>
</div>
