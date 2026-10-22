<?php

class StockController {
    public function showInForm() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM items WHERE is_active = 1 ORDER BY nama ASC");
        $items = $stmt->fetchAll();

        $selectedItemId = $_GET['item_id'] ?? null;
        $idempotencyKey = generateIdempotencyKey();

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        ob_start();
        require_once __DIR__ . '/../../views/stock/in.php';
        $content = ob_get_clean();

        $title = 'Stok Masuk - Gudang ATK';
        require_once __DIR__ . '/../../views/layout.php';
    }

    public function storeIn() {
        $data = [
            'item_id' => $_POST['item_id'],
            'jumlah' => $_POST['jumlah'],
            'arah' => 1,
            'tipe' => 'masuk',
            'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
            'pihak' => $_POST['pihak'] ?? null,
            'keterangan' => $_POST['keterangan'] ?? null,
            'created_by' => currentUserId(),
            'idempotency_key' => $_POST['idempotency_key']
        ];

        $result = StockService::catat($data);

        if ($result['success']) {
            Flash::set('success', $result['message']);
            redirect('dashboard');
        } else {
            Flash::set('error', $result['message']);
            redirect('stock/in');
        }
    }

    public function showOutForm() {
        $db = Database::getInstance();
        $stmt = $db->query("
            SELECT i.*, sb.saldo 
            FROM items i 
            JOIN stock_balances sb ON i.id = sb.item_id 
            ORDER BY i.nama ASC
        ");
        $items = $stmt->fetchAll();

        $selectedItemId = $_GET['item_id'] ?? null;
        $idempotencyKey = generateIdempotencyKey();

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        ob_start();
        require_once __DIR__ . '/../../views/stock/out.php';
        $content = ob_get_clean();

        $title = 'Stok Keluar - Gudang ATK';
        require_once __DIR__ . '/../../views/layout.php';
    }

    public function storeOut() {
        $data = [
            'item_id' => $_POST['item_id'],
            'jumlah' => $_POST['jumlah'],
            'arah' => -1,
            'tipe' => 'keluar',
            'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
            'pihak' => $_POST['pihak'] ?? null,
            'keterangan' => $_POST['keterangan'] ?? null,
            'created_by' => currentUserId(),
            'idempotency_key' => $_POST['idempotency_key']
        ];

        $result = StockService::catat($data);

        if ($result['success']) {
            Flash::set('success', $result['message']);
            redirect('dashboard');
        } else {
            Flash::set('error', $result['message']);
            redirect('stock/out');
        }
    }

    public function correct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('items');
        }

        $refId = $_POST['ref_transaction_id'];
        $keterangan = $_POST['keterangan'];
        $idempotencyKey = $_POST['idempotency_key'];

        $db = Database::getInstance();
        
        // 1. Dapatkan transaksi asal
        $stmt = $db->prepare("SELECT * FROM stock_transactions WHERE id = ?");
        $stmt->execute([$refId]);
        $origin = $stmt->fetch();

        if (!$origin) {
            Flash::set('error', 'Transaksi asal tidak ditemukan.');
            redirect('items');
        }

        // 2. Cek apakah sudah pernah dikoreksi
        $stmt = $db->prepare("SELECT id FROM stock_transactions WHERE ref_transaction_id = ?");
        $stmt->execute([$refId]);
        if ($stmt->fetch()) {
            Flash::set('error', 'Transaksi ini sudah pernah dikoreksi sebelumnya.');
            redirect('items/history?id=' . $origin['item_id']);
        }

        // 3. Tentukan tipe koreksi (kebalikan)
        $tipe = ($origin['tipe'] == 'masuk' || $origin['tipe'] == 'koreksi_masuk') ? 'koreksi_keluar' : 'koreksi_masuk';
        $arah = ($origin['arah'] == 1) ? -1 : 1;

        $data = [
            'item_id' => $origin['item_id'],
            'jumlah' => $origin['jumlah'],
            'arah' => $arah,
            'tipe' => $tipe,
            'tanggal' => date('Y-m-d'),
            'pihak' => $origin['pihak'],
            'keterangan' => '[KOREKSI #' . $refId . '] ' . $keterangan,
            'created_by' => currentUserId(),
            'idempotency_key' => $idempotencyKey,
            'ref_transaction_id' => $refId
        ];

        $result = StockService::catat($data);

        if ($result['success']) {
            Flash::set('success', 'Koreksi berhasil disimpan.');
        } else {
            Flash::set('error', 'Koreksi gagal: ' . $result['message']);
        }

        redirect('items/history?id=' . $origin['item_id']);
    }
}
