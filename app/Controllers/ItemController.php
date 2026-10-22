<?php

class ItemController {
    public function index() {
        $db = Database::getInstance();
        $stmt = $db->query("
            SELECT i.*, sb.saldo 
            FROM items i 
            LEFT JOIN stock_balances sb ON i.id = sb.item_id 
            ORDER BY i.nama ASC
        ");
        $items = $stmt->fetchAll();

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        ob_start();
        require_once __DIR__ . '/../../views/items/index.php';
        $content = ob_get_clean();

        $title = 'Daftar Barang - Gudang ATK';
        require_once __DIR__ . '/../../views/layout.php';
    }

    public function create() {
        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        ob_start();
        require_once __DIR__ . '/../../views/items/create.php';
        $content = ob_get_clean();

        $title = 'Tambah Barang - Gudang ATK';
        require_once __DIR__ . '/../../views/layout.php';
    }

    public function store() {
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Csrf::validateToken($csrfToken)) {
            Flash::set('error', 'Invalid CSRF token');
            redirect('items/create');
        }

        $kode = $_POST['kode'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $satuan = $_POST['satuan'] ?? '';
        $stok_minimum = $_POST['stok_minimum'] ?? 0;

        $db = Database::getInstance();
        
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO items (kode, nama, satuan, stok_minimum) VALUES (?, ?, ?, ?)");
            $stmt->execute([$kode, $nama, $satuan, $stok_minimum]);
            $itemId = $db->lastInsertId();

            // Inisialisasi saldo
            $stmt = $db->prepare("INSERT INTO stock_balances (item_id, saldo) VALUES (?, 0)");
            $stmt->execute([$itemId]);

            $db->commit();
            Flash::set('success', 'Barang berhasil ditambahkan');
            redirect('items');
        } catch (Exception $e) {
            $db->rollBack();
            Flash::set('error', 'Gagal menambah barang: ' . $e->getMessage());
            redirect('items/create');
        }
    }
}
