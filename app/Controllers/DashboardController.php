<?php

class DashboardController {
    public function index() {
        $db = Database::getInstance();
        
        // Total Items
        $stmt = $db->query("SELECT COUNT(*) as total FROM items WHERE is_active = 1");
        $totalItems = $stmt->fetch()['total'];

        // Transaksi Hari Ini
        $stmt = $db->query("SELECT COUNT(*) as total FROM stock_transactions WHERE tanggal = CURDATE()");
        $todayTransactions = $stmt->fetch()['total'];

        // Total Masuk & Keluar Bulan Ini
        $stmt = $db->query("
            SELECT 
                SUM(CASE WHEN arah = 1 THEN jumlah ELSE 0 END) as total_masuk,
                SUM(CASE WHEN arah = -1 THEN jumlah ELSE 0 END) as total_keluar
            FROM stock_transactions 
            WHERE tanggal LIKE '" . date('Y-m') . "%'
        ");
        $monthlyStats = $stmt->fetch();
        $totalMasukMonth = $monthlyStats['total_masuk'] ?? 0;
        $totalKeluarMonth = $monthlyStats['total_keluar'] ?? 0;

        // Stok Rendah (saldo < stok_minimum)
        $stmt = $db->query("
            SELECT i.nama, sb.saldo, i.stok_minimum, i.kode 
            FROM items i 
            JOIN stock_balances sb ON i.id = sb.item_id 
            WHERE sb.saldo < i.stok_minimum
        ");
        $lowStockItems = $stmt->fetchAll();

        // 10 Transaksi Terakhir
        $stmt = $db->query("
            SELECT st.*, i.nama, u.nama as nama_user 
            FROM stock_transactions st
            JOIN items i ON st.item_id = i.id
            JOIN users u ON st.created_by = u.id
            ORDER BY st.created_at DESC
            LIMIT 10
        ");
        $recentTransactions = $stmt->fetchAll();

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];
        
        ob_start();
        require_once __DIR__ . '/../../views/dashboard.php';
        $content = ob_get_clean();
        
        $title = 'Dashboard - Gudang ATK';
        require_once __DIR__ . '/../../views/layout.php';
    }
}
