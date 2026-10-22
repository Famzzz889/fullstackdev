<?php
/**
 * @var string $baseUrl
 * @var string $idempotencyKey
 * @var array $items
 * @var int|null $selectedItemId
 */
?>
<div class="max-w-2xl mx-auto space-y-6" x-data="{ 
    submitting: false,
    selectedItem: <?= $selectedItemId ?? 'null' ?>,
    items: <?= json_encode(array_column($items, null, 'id')) ?>,
    get currentItem() {
        return this.selectedItem ? this.items[this.selectedItem] : null;
    }
}">
    <div class="flex items-center space-x-4">
        <a href="<?= e($baseUrl) ?>/dashboard" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Catat Stok Masuk</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form action="<?= e($baseUrl) ?>/stock/in" method="POST" class="space-y-6" @submit="submitting = true">
            <?= Csrf::csrf_field() ?>
            <input type="hidden" name="idempotency_key" value="<?= e($idempotencyKey) ?>">
            
            <div class="space-y-4">
                <div>
                    <label for="item_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Barang</label>
                    <select name="item_id" id="item_id" required x-model="selectedItem"
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>">
                                <?= e($item['kode']) ?> - <?= e($item['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <template x-if="currentItem">
                        <p class="mt-2 text-sm text-slate-500">
                            Stok saat ini: <span class="font-bold text-slate-900" x-text="currentItem.saldo"></span> <span x-text="currentItem.satuan"></span>
                        </p>
                    </template>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jumlah" class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Masuk</label>
                        <input type="number" name="jumlah" id="jumlah" required min="1" 
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label for="tanggal" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" id="tanggal" required value="<?= date('Y-m-d') ?>" 
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <div>
                    <label for="pihak" class="block text-sm font-semibold text-slate-700 mb-2">Pihak (Supplier/Pengirim)</label>
                    <input type="text" name="pihak" id="pihak" placeholder="Contoh: PT Stationery Jaya" 
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-slate-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" placeholder="Tambahkan catatan jika perlu" 
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"></textarea>
                </div>
            </div>

            <div class="pt-6 flex justify-end space-x-3 border-t border-slate-100">
                <a href="<?= e($baseUrl) ?>/dashboard" class="inline-flex justify-center py-2.5 px-6 border border-slate-300 shadow-sm text-sm font-semibold rounded-xl text-slate-700 bg-white hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit" :disabled="submitting" 
                    class="inline-flex justify-center py-2.5 px-8 border border-transparent shadow-sm text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-60 transition-all">
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
</div>
