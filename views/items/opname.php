<?php
/**
 * @var string $baseUrl
 * @var array $item
 * @var string $idempotencyKey
 */
?>
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center space-x-4">
        <a href="<?= e($baseUrl) ?>/items/history?id=<?= $item['id'] ?>" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Stock Opname</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8" x-data="{ 
        sistem: <?= (int)$item['saldo'] ?>,
        fisik: <?= (int)$item['saldo'] ?>,
        get selisih() { return this.fisik - this.sistem },
        showConfirm: false
    }">
        <div class="mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Barang</p>
                <p class="text-sm font-bold text-slate-900"><?= e($item['kode']) ?> - <?= e($item['nama']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Saldo Sistem</p>
                <p class="text-lg font-bold text-indigo-600"><?= formatNumber($item['saldo']) ?> <?= e($item['satuan']) ?></p>
            </div>
        </div>

        <form action="<?= e($baseUrl) ?>/items/opname" method="POST" @submit.prevent="showConfirm = true" class="space-y-6">
            <?= Csrf::csrf_field() ?>
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
            <input type="hidden" name="stok_sistem" :value="sistem">
            <input type="hidden" name="idempotency_key" value="<?= e($idempotencyKey) ?>">

            <div>
                <label for="stok_fisik" class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Stok Fisik</label>
                <input type="number" name="stok_fisik" id="stok_fisik" required min="0" x-model.number="fisik"
                    class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-2xl font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <p class="mt-2 text-sm text-slate-500">Masukkan jumlah barang yang dihitung secara fisik di gudang.</p>
            </div>

            <div class="p-4 rounded-2xl border flex items-center justify-between transition-all" 
                :class="selisih == 0 ? 'bg-slate-50 border-slate-200' : (selisih > 0 ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100')">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest" :class="selisih == 0 ? 'text-slate-400' : (selisih > 0 ? 'text-emerald-500' : 'text-rose-500')">Selisih</p>
                    <p class="text-xl font-bold" :class="selisih == 0 ? 'text-slate-900' : (selisih > 0 ? 'text-emerald-700' : 'text-rose-700')">
                        <span x-show="selisih > 0">+</span><span x-text="selisih"></span>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium" :class="selisih == 0 ? 'text-slate-500' : (selisih > 0 ? 'text-emerald-600' : 'text-rose-600')" x-text="selisih == 0 ? 'Stok Sesuai' : (selisih > 0 ? 'Kelebihan Stok' : 'Kekurangan Stok')"></p>
                </div>
            </div>

            <div class="pt-6 flex justify-end">
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-8 py-2.5 font-semibold transition-all">
                    Proses Opname
                </button>
            </div>

            <!-- Modal Konfirmasi -->
            <div x-show="showConfirm" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showConfirm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="showConfirm = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    <div x-show="showConfirm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block w-full max-w-md p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl sm:align-middle">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Konfirmasi Opname</h3>
                        <p class="text-sm text-slate-500 mb-6">Anda akan menyesuaikan stok sistem dari <span class="font-bold" x-text="sistem"></span> menjadi <span class="font-bold" x-text="fisik"></span>. Tindakan ini tidak dapat dibatalkan.</p>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="showConfirm = false" class="px-6 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-xl transition-all">Batal</button>
                            <button type="button" @click="$el.closest('form').submit()" class="px-8 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-200 transition-all">Ya, Sesuaikan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
