<?php
/**
 * Script untuk menguji idempotency_key dan locking database
 * Jalankan lewat CLI: php tests/concurrency_test.php
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/Database.php';
require_once __DIR__ . '/../app/Services/StockService.php';

echo "--- CONCURRENCY & IDEMPOTENCY TEST ---\n";

$itemId = 1; // Kertas A4
$userId = 1; // Admin
$key = bin2hex(random_bytes(16));

$data = [
    'item_id' => $itemId,
    'jumlah' => 10,
    'arah' => 1,
    'tipe' => 'masuk',
    'alasan' => 'Test Konkurensi',
    'created_by' => $userId,
    'idempotency_key' => $key
];

echo "Mengirim request pertama dengan key: $key\n";
$res1 = StockService::catat($data);
echo "Respon 1: " . ($res1['success'] ? "SUCCESS" : "FAILED") . " - " . $res1['message'] . " (Code: " . $res1['code'] . ")\n";

echo "Mengirim request kedua dengan key yang SAMA: $key\n";
$res2 = StockService::catat($data);
echo "Respon 2: " . ($res2['success'] ? "SUCCESS" : "FAILED") . " - " . $res2['message'] . " (Code: " . $res2['code'] . ")\n";

if ($res2['code'] === 'DUPLICATE') {
    echo "VERIFIED: Anti-double submission bekerja!\n";
} else {
    echo "FAILED: Idempotency tidak terdeteksi!\n";
}

echo "\nMengetes Stok Tidak Cukup (Negative Stock Protection)\n";
$dataOut = [
    'item_id' => $itemId,
    'jumlah' => 9999999, // Jumlah besar
    'arah' => -1,
    'tipe' => 'keluar',
    'alasan' => 'Test Negative Stock',
    'created_by' => $userId,
    'idempotency_key' => bin2hex(random_bytes(16))
];

$res3 = StockService::catat($dataOut);
echo "Respon Keluar: " . ($res3['success'] ? "SUCCESS" : "FAILED") . " - " . $res3['message'] . " (Code: " . $res3['code'] . ")\n";

if ($res3['code'] === 'INSUFFICIENT_STOCK') {
    echo "VERIFIED: Proteksi stok minus bekerja!\n";
} else {
    echo "FAILED: Stok minus diizinkan!\n";
}
