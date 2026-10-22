<?php

class StockController {
    public function showInForm() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM items WHERE is_active = 1 ORDER BY nama_barang ASC");
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
}
