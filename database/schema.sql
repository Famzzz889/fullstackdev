CREATE DATABASE IF NOT EXISTS inventory_at;
USE inventory_at;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS stock_transactions;
DROP TABLE IF EXISTS stock_balances;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Items
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    satuan VARCHAR(20) NOT NULL,
    stok_minimum INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Stock Balances (Snapshot Saldo Terkini)
CREATE TABLE IF NOT EXISTS stock_balances (
    item_id INT PRIMARY KEY,
    saldo INT NOT NULL DEFAULT 0,
    CONSTRAINT chk_saldo_non_negative CHECK (saldo >= 0),
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Stock Transactions (Ledger Append-Only)
CREATE TABLE IF NOT EXISTS stock_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idempotency_key VARCHAR(64) NOT NULL UNIQUE,
    item_id INT NOT NULL,
    tipe ENUM('masuk', 'keluar', 'koreksi_masuk', 'koreksi_keluar', 'penyesuaian_opname') NOT NULL,
    jumlah INT UNSIGNED NOT NULL,
    arah TINYINT(1) NOT NULL COMMENT '1: Tambah, -1: Kurang',
    tanggal DATE NOT NULL,
    pihak VARCHAR(150) DEFAULT NULL,
    keterangan TEXT DEFAULT NULL,
    ref_transaction_id INT DEFAULT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (ref_transaction_id) REFERENCES stock_transactions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index untuk performa dan filter
CREATE INDEX idx_stock_transactions_item_date ON stock_transactions(item_id, tanggal);
CREATE INDEX idx_stock_transactions_created_at ON stock_transactions(created_at);
