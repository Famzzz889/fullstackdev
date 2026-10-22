<?php

/**
 * Format angka ke format Indonesia (1.250)
 */
function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}

/**
 * Format ke Rupiah (Rp 1.250)
 */
function formatRupiah($number) {
    return 'Rp ' . formatNumber($number);
}

/**
 * Format tanggal ke format Indonesia (22 Okt 2026)
 */
function formatDate($date) {
    if (!$date) return '-';
    $months = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];
    
    $timestamp = strtotime($date);
    $d = date('j', $timestamp);
    $m = (int)date('n', $timestamp);
    $y = date('Y', $timestamp);
    
    return "{$d} {$months[$m]} {$y}";
}

/**
 * Mendapatkan nilai input lama setelah redirect (flash data)
 */
function old($key, $default = '') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $value = $_SESSION['old'][$key] ?? $default;
    unset($_SESSION['old'][$key]);
    return $value;
}

/**
 * Escape HTML output
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect ke URL tertentu
 */
function redirect($path) {
    $config = require __DIR__ . '/config.php';
    $baseUrl = rtrim($config['app']['base_url'], '/');
    $path = ltrim($path, '/');
    header("Location: {$baseUrl}/{$path}");
    exit;
}

/**
 * Generate Idempotency Key
 */
function generateIdempotencyKey() {
    return bin2hex(random_bytes(16));
}

/**
 * Get current user ID
 */
function currentUserId() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION['user_id'] ?? null;
}
