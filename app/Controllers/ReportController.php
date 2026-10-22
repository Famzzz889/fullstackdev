<?php

class ReportController {
    public function index() {
        $db = Database::getInstance();
        
        $itemId = $_GET['item_id'] ?? null;
        $month = $_GET['month'] ?? date('Y-m');
        
        if (!$itemId) {
            $stmt = $db->query("SELECT id, nama, kode FROM items ORDER BY nama ASC");
            $items = $stmt->fetchAll();
            $config = require __DIR__ . '/../../app/config.php';
            $baseUrl = $config['app']['base_url'];
            $title = 'Laporan Stok - Gudang ATK';
            ob_start();
            require_once __DIR__ . '/../../views/reports/index.php';
            $content = ob_get_clean();
            require_once __DIR__ . '/../../views/layout.php';
            return;
        }

        // 1. Get Item Detail
        $stmt = $db->prepare("SELECT * FROM items WHERE id = ?");
        $stmt->execute([$itemId]);
        $item = $stmt->fetch();

        // 2. Saldo Awal (sebelum awal bulan)
        $firstDayOfMonth = $month . '-01';
        $stmt = $db->prepare("
            SELECT COALESCE(SUM(jumlah * arah), 0) as saldo_awal 
            FROM stock_transactions 
            WHERE item_id = ? AND tanggal < ?
        ");
        $stmt->execute([$itemId, $firstDayOfMonth]);
        $saldoAwal = (int)$stmt->fetch()['saldo_awal'];

        // 3. Mutasi dalam bulan
        $stmt = $db->prepare("
            SELECT st.*, u.nama as nama_user 
            FROM stock_transactions st
            JOIN users u ON st.created_by = u.id
            WHERE st.item_id = ? AND st.tanggal LIKE ?
            ORDER BY st.tanggal ASC, st.created_at ASC
        ");
        $stmt->execute([$itemId, $month . '%']);
        $mutasi = $stmt->fetchAll();

        // 4. Hitung Total Masuk, Keluar, dan Saldo Akhir
        $totalMasuk = 0;
        $totalKeluar = 0;
        $runningBalance = $saldoAwal;
        foreach ($mutasi as &$tx) {
            if ($tx['arah'] > 0) $totalMasuk += $tx['jumlah'];
            else $totalKeluar += $tx['jumlah'];
            
            $runningBalance += ($tx['jumlah'] * $tx['arah']);
            $tx['saldo_berjalan'] = $runningBalance;
        }
        $saldoAkhir = $runningBalance;

        // Get all items for filter
        $stmt = $db->query("SELECT id, nama, kode FROM items ORDER BY nama ASC");
        $items = $stmt->fetchAll();

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        if (isset($_GET['export']) && $_GET['export'] == 'excel') {
            if (ob_get_level()) ob_end_clean();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=kartu-stok-' . $item['kode'] . '-' . $month . '.xls');
            require_once __DIR__ . '/../../views/reports/excel_kartu_stok.php';
            exit;
        }

        ob_start();
        require_once __DIR__ . '/../../views/reports/kartu_stok.php';
        $content = ob_get_clean();

        $title = 'Kartu Stok - ' . $item['nama'];
        require_once __DIR__ . '/../../views/layout.php';
    }

    public function consistency() {
        $db = Database::getInstance();
        
        $stmt = $db->query("
            SELECT 
                i.id, i.kode, i.nama, 
                sb.saldo as saldo_sistem,
                (SELECT COALESCE(SUM(st.jumlah * st.arah), 0) FROM stock_transactions st WHERE st.item_id = i.id) as saldo_ledger
            FROM items i
            JOIN stock_balances sb ON i.id = sb.item_id
            ORDER BY i.nama ASC
        ");
        $results = $stmt->fetchAll();

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        ob_start();
        require_once __DIR__ . '/../../views/reports/consistency.php';
        $content = ob_get_clean();

        $title = 'Cek Konsistensi Data - Gudang ATK';
        require_once __DIR__ . '/../../views/layout.php';
    }
}
