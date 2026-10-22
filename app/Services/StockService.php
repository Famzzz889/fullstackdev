<?php

class StockService {
    /**
     * Catat transaksi stok dengan pola anti-double dan validasi saldo
     * 
     * @param array $data [item_id, jumlah, arah, tipe, tanggal, pihak, keterangan, created_by, idempotency_key, ref_transaction_id]
     * @return array [success, message, code]
     */
    public static function catat(array $data) {
        $db = Database::getInstance();
        
        try {
            // 1. BEGIN
            $db->beginTransaction();

            // 2. Pastikan baris stock_balances ada, lalu SELECT FOR UPDATE
            $stmt = $db->prepare("SELECT saldo FROM stock_balances WHERE item_id = ? FOR UPDATE");
            $stmt->execute([$data['item_id']]);
            $balance = $stmt->fetch();
            
            if (!$balance) {
                // Jika belum ada (seharusnya sudah ada saat create item), insert dulu
                $db->prepare("INSERT INTO stock_balances (item_id, saldo) VALUES (?, 0)")->execute([$data['item_id']]);
                $currentSaldo = 0;
            } else {
                $currentSaldo = (int)$balance['saldo'];
            }

            // 3. Cek idempotency_key
            $stmt = $db->prepare("SELECT id FROM stock_transactions WHERE idempotency_key = ?");
            $stmt->execute([$data['idempotency_key']]);
            if ($stmt->fetch()) {
                $db->rollBack();
                return [
                    'success' => true,
                    'message' => 'Transaksi sudah diproses sebelumnya',
                    'code' => 'DUPLICATE'
                ];
            }

            // 4. Validasi stok tidak boleh minus
            $jumlah = (int)$data['jumlah'];
            $arah = (int)$data['arah'];
            $newSaldo = $currentSaldo + ($jumlah * $arah);

            if ($newSaldo < 0) {
                $db->rollBack();
                return [
                    'success' => false,
                    'message' => "Stok tidak cukup. Tersedia " . formatNumber($currentSaldo) . " satuan, diminta " . formatNumber($jumlah) . " satuan.",
                    'code' => 'INSUFFICIENT_STOCK'
                ];
            }

            // 5. INSERT ke stock_transactions
            $stmt = $db->prepare("
                INSERT INTO stock_transactions 
                (idempotency_key, item_id, tipe, jumlah, arah, tanggal, pihak, keterangan, ref_transaction_id, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['idempotency_key'],
                $data['item_id'],
                $data['tipe'],
                $jumlah,
                $arah,
                $data['tanggal'] ?? date('Y-m-d'),
                $data['pihak'] ?? null,
                $data['keterangan'] ?? null,
                $data['ref_transaction_id'] ?? null,
                $data['created_by']
            ]);

            // 6. UPDATE stock_balances
            $stmt = $db->prepare("UPDATE stock_balances SET saldo = ? WHERE item_id = ?");
            $stmt->execute([$newSaldo, $data['item_id']]);

            // 7. COMMIT
            $db->commit();

            return [
                'success' => true,
                'message' => 'Transaksi berhasil dicatat',
                'code' => 'SUCCESS'
            ];

        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 'ERROR'
            ];
        }
    }
}
