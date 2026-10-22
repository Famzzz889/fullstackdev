USE inventory_at;

-- Clear existing data
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE stock_transactions;
TRUNCATE TABLE stock_balances;
TRUNCATE TABLE items;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

-- Seed Users (password: password123)
-- Hash generated via password_hash('password123', PASSWORD_BCRYPT)
INSERT INTO users (nama, email, password) VALUES 
('Administrator', 'admin@atk.test', '$2y$10$g8q7SPs5kd9aGbFNeJtBuewrpCbSTZxDlaLsy9sVf2s0/GMYa0Pwa'),
('Petugas Gudang', 'gudang@atk.test', '$2y$10$g8q7SPs5kd9aGbFNeJtBuewrpCbSTZxDlaLsy9sVf2s0/GMYa0Pwa');

-- Seed Items (15 Items)
INSERT INTO items (kode, nama, satuan, stok_minimum) VALUES 
('P001', 'Pulpen Pilot G2 Hitam', 'Box', 5),
('P002', 'Pulpen Pilot G2 Biru', 'Box', 5),
('B001', 'Buku Tulis Sinar Dunia 38', 'Pack', 10),
('B002', 'Buku Tulis Sinar Dunia 58', 'Pack', 10),
('K001', 'Kertas A4 80gr PaperOne', 'Rim', 20),
('K002', 'Kertas F4 80gr PaperOne', 'Rim', 20),
('S001', 'Spidol Boardmarker Snowman Hitam', 'Pcs', 12),
('S002', 'Spidol Boardmarker Snowman Biru', 'Pcs', 12),
('T001', 'Stapler Max HD-10', 'Pcs', 5),
('T002', 'Isi Staples Max No. 10', 'Box', 20),
('H001', 'Penghapus Joyko EB-30', 'Pcs', 24),
('G001', 'Penggaris Besi 30cm', 'Pcs', 10),
('L001', 'Lakban Bening 2 Inch', 'Roll', 6),
('M001', 'Map Snelhecter Plastik Biru', 'Lusin', 5),
('M002', 'Map Snelhecter Plastik Merah', 'Lusin', 5);

-- Initial Stock Balances
INSERT INTO stock_balances (item_id, saldo) 
SELECT id, 0 FROM items;

-- Seed Transactions (Spread over 3 months)
-- Bulan 1 (Agustus 2026)
INSERT INTO stock_transactions (idempotency_key, item_id, tipe, jumlah, arah, tanggal, pihak, keterangan, created_by) VALUES
(SHA2('tx1', 256), 1, 'masuk', 50, 1, '2026-08-05', 'PT Stationery Jaya', 'Stok awal', 1),
(SHA2('tx2', 256), 3, 'masuk', 100, 1, '2026-08-10', 'PT Stationery Jaya', 'Stok awal', 1),
(SHA2('tx3', 256), 5, 'masuk', 200, 1, '2026-08-15', 'PT Kertas Makmur', 'Stok awal', 1);

-- Bulan 2 (September 2026)
INSERT INTO stock_transactions (idempotency_key, item_id, tipe, jumlah, arah, tanggal, pihak, keterangan, created_by) VALUES
(SHA2('tx4', 256), 1, 'keluar', 10, -1, '2026-09-02', 'Divisi Finance', 'Kebutuhan kantor', 2),
(SHA2('tx5', 256), 3, 'keluar', 20, -1, '2026-09-05', 'Divisi HRD', 'Kebutuhan kantor', 2),
(SHA2('tx6', 256), 5, 'keluar', 50, -1, '2026-09-10', 'Divisi Marketing', 'Kebutuhan kantor', 2),
(SHA2('tx7', 256), 10, 'masuk', 50, 1, '2026-09-15', 'PT Staples Indo', 'Restock', 1);

-- Bulan 3 (Oktober 2026)
INSERT INTO stock_transactions (idempotency_key, item_id, tipe, jumlah, arah, tanggal, pihak, keterangan, created_by) VALUES
(SHA2('tx8', 256), 1, 'keluar', 38, -1, '2026-10-01', 'Divisi IT', 'Kebutuhan kantor', 2), -- Sisa 2 (Low stock)
(SHA2('tx9', 256), 3, 'keluar', 75, -1, '2026-10-05', 'Divisi IT', 'Kebutuhan kantor', 2),  -- Sisa 5 (Low stock)
(SHA2('tx10', 256), 10, 'keluar', 45, -1, '2026-10-10', 'Divisi HRD', 'Kebutuhan kantor', 2), -- Sisa 5 (Low stock)
(SHA2('tx11', 256), 5, 'koreksi_masuk', 5, 1, '2026-10-15', NULL, 'Koreksi kelebihan catat keluar sebelumnya', 1); -- Sisa 155

-- Update stock_balances based on transactions
UPDATE stock_balances sb 
SET saldo = (
    SELECT COALESCE(SUM(CAST(st.jumlah AS SIGNED) * st.arah), 0) 
    FROM stock_transactions st 
    WHERE st.item_id = sb.item_id
);
