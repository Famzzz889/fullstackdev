<?php

class ReportController {
    public function index() {
        $db = Database::getInstance();
        
        $itemId = $_GET['item_id'] ?? null;
        $tipe = $_GET['tipe'] ?? null;
        
        $query = "
            SELECT st.*, i.nama, i.kode, i.satuan, u.nama as nama_user 
            FROM stock_transactions st
            JOIN items i ON st.item_id = i.id
            JOIN users u ON st.created_by = u.id
            WHERE 1=1
        ";
        $params = [];

        if ($itemId) {
            $query .= " AND st.item_id = ?";
            $params[] = $itemId;
        }

        if ($tipe) {
            $query .= " AND st.tipe = ?";
            $params[] = $tipe;
        }

        $query .= " ORDER BY st.tanggal DESC, st.created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $transactions = $stmt->fetchAll();

        // Get all items for filter
        $stmt = $db->query("SELECT id, nama, kode FROM items ORDER BY nama ASC");
        $items = $stmt->fetchAll();

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        ob_start();
        require_once __DIR__ . '/../../views/reports/index.php';
        $content = ob_get_clean();

        $title = 'Laporan Stok - Gudang ATK';
        require_once __DIR__ . '/../../views/layout.php';
    }
}
