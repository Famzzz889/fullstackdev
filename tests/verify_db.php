<?php
/**
 * Script Verifikasi Konsistensi Database
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/Database.php';

$db = Database::getInstance();

echo "--- VERIFIKASI KONSISTENSI DATABASE ---\n";

// 1. Cek User
$stmt = $db->query("SELECT COUNT(*) as total FROM users");
$userCount = $stmt->fetch()['total'];
echo "Total User: $userCount (Expected: 2)\n";

// 2. Cek Item
$stmt = $db->query("SELECT COUNT(*) as total FROM items");
$itemCount = $stmt->fetch()['total'];
echo "Total Item: $itemCount (Expected: 15)\n";

// 3. Verifikasi Saldo vs Ledger
echo "\nVerifikasi Saldo vs Ledger:\n";
$stmt = $db->query("
    SELECT 
        i.nama, 
        sb.saldo as snapshot_saldo,
        (SELECT SUM(st.jumlah * st.arah) FROM stock_transactions st WHERE st.item_id = i.id) as ledger_saldo
    FROM items i
    JOIN stock_balances sb ON i.id = sb.item_id
");

$inconsistent = false;
foreach ($stmt->fetchAll() as $row) {
    $snapshot = (int)$row['snapshot_saldo'];
    $ledger = (int)($row['ledger_saldo'] ?? 0);
    
    if ($snapshot !== $ledger) {
        echo "[FAILED] {$row['nama']}: Snapshot ($snapshot) != Ledger ($ledger)\n";
        $inconsistent = true;
    }
}

if (!$inconsistent) {
    echo "[SUCCESS] Semua saldo konsisten dengan ledger.\n";
}

// 4. Cek Stok Rendah
echo "\nCek Stok Rendah:\n";
$stmt = $db->query("
    SELECT nama, saldo, stok_minimum 
    FROM items i
    JOIN stock_balances sb ON i.id = sb.item_id
    WHERE saldo < stok_minimum
");

$lowStockItems = $stmt->fetchAll();
echo "Total Barang Stok Rendah: " . count($lowStockItems) . " (Expected: >= 2)\n";
foreach ($lowStockItems as $item) {
    echo "- {$item['nama']}: Saldo {$item['saldo']} (Min: {$item['stok_minimum']})\n";
}

if (count($lowStockItems) >= 2) {
    echo "[SUCCESS] Kriteria stok rendah terpenuhi.\n";
} else {
    echo "[FAILED] Kurang dari 2 barang dengan stok rendah.\n";
}
