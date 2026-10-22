<?php

class ItemController {
    public function index() {
        $db = Database::getInstance();
        
        $search = $_GET['q'] ?? '';
        $filterLowStock = isset($_GET['low_stock']) && $_GET['low_stock'] == '1';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $where = "WHERE (i.nama LIKE ? OR i.kode LIKE ?)";
        if ($filterLowStock) {
            $where .= " AND sb.saldo < i.stok_minimum";
        }

        $query = "
            SELECT i.*, sb.saldo 
            FROM items i 
            LEFT JOIN stock_balances sb ON i.id = sb.item_id 
            $where
            ORDER BY i.nama ASC
            LIMIT ? OFFSET ?
        ";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(2, "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(3, $perPage, PDO::PARAM_INT);
        $stmt->bindValue(4, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        // Count total for pagination
        $stmtCount = $db->prepare("SELECT COUNT(*) FROM items i LEFT JOIN stock_balances sb ON i.id = sb.item_id $where");
        $stmtCount->execute(["%$search%", "%$search%"]);
        $totalItems = $stmtCount->fetchColumn();
        $totalPages = ceil($totalItems / $perPage);

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        ob_start();
        require_once __DIR__ . '/../../views/items/index.php';
        $content = ob_get_clean();

        $title = 'Daftar Barang - Gudang ATK';
        require_once __DIR__ . '/../../views/layout.php';
    }

    public function history() {
        $db = Database::getInstance();
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            redirect('items');
        }

        // Get Item Detail
        $stmt = $db->prepare("SELECT i.*, sb.saldo FROM items i LEFT JOIN stock_balances sb ON i.id = sb.item_id WHERE i.id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        if (!$item) {
            redirect('items');
        }

        // Get History
        $stmt = $db->prepare("
            SELECT st.*, u.nama as nama_user 
            FROM stock_transactions st
            JOIN users u ON st.created_by = u.id
            WHERE st.item_id = ?
            ORDER BY st.tanggal DESC, st.created_at DESC
        ");
        $stmt->execute([$id]);
        $history = $stmt->fetchAll();

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        ob_start();
        require_once __DIR__ . '/../../views/items/history.php';
        $content = ob_get_clean();

        $title = 'Riwayat Barang - ' . $item['nama'];
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
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Csrf::validateToken($csrfToken)) {
            Flash::set('error', 'Token keamanan tidak valid.');
            redirect('items/create');
        }

        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $satuan = trim($_POST['satuan'] ?? '');
        $stok_minimum = (int)($_POST['stok_minimum'] ?? 0);

        $_SESSION['old'] = $_POST;

        // Validasi
        if (empty($kode) || empty($nama) || empty($satuan)) {
            Flash::set('error', 'Semua kolom wajib diisi.');
            redirect('items/create');
        }

        if ($stok_minimum < 0) {
            Flash::set('error', 'Stok minimum tidak boleh kurang dari 0.');
            redirect('items/create');
        }

        $db = Database::getInstance();
        
        // Cek Keunikan Kode
        $stmt = $db->prepare("SELECT id FROM items WHERE kode = ?");
        $stmt->execute([$kode]);
        if ($stmt->fetch()) {
            Flash::set('error', 'Kode barang sudah dipakai.');
            redirect('items/create');
        }
        
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO items (kode, nama, satuan, stok_minimum) VALUES (?, ?, ?, ?)");
            $stmt->execute([$kode, $nama, $satuan, $stok_minimum]);
            $itemId = $db->lastInsertId();

            // Inisialisasi saldo 0
            $stmt = $db->prepare("INSERT INTO stock_balances (item_id, saldo) VALUES (?, 0)");
            $stmt->execute([$itemId]);

            $db->commit();
            unset($_SESSION['old']);
            Flash::set('success', 'Barang "' . e($nama) . '" berhasil ditambahkan.');
            redirect('items');
        } catch (Exception $e) {
            $db->rollBack();
            Flash::set('error', 'Gagal menambah barang: ' . $e->getMessage());
            redirect('items/create');
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('items');

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM items WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        if (!$item) {
            Flash::set('error', 'Barang tidak ditemukan.');
            redirect('items');
        }

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];

        ob_start();
        require_once __DIR__ . '/../../views/items/edit.php';
        $content = ob_get_clean();

        $title = 'Edit Barang - ' . e($item['nama']);
        require_once __DIR__ . '/../../views/layout.php';
    }

    public function update() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $id = $_POST['id'] ?? null;
        if (!$id) redirect('items');

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Csrf::validateToken($csrfToken)) {
            Flash::set('error', 'Token keamanan tidak valid.');
            redirect('items/edit?id=' . $id);
        }

        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $satuan = trim($_POST['satuan'] ?? '');
        $stok_minimum = (int)($_POST['stok_minimum'] ?? 0);

        // Validasi
        if (empty($kode) || empty($nama) || empty($satuan)) {
            Flash::set('error', 'Semua kolom wajib diisi.');
            redirect('items/edit?id=' . $id);
        }

        if ($stok_minimum < 0) {
            Flash::set('error', 'Stok minimum tidak boleh kurang dari 0.');
            redirect('items/edit?id=' . $id);
        }

        $db = Database::getInstance();
        
        // Cek Keunikan Kode (kecuali barang ini sendiri)
        $stmt = $db->prepare("SELECT id FROM items WHERE kode = ? AND id != ?");
        $stmt->execute([$kode, $id]);
        if ($stmt->fetch()) {
            Flash::set('error', 'Kode barang sudah dipakai.');
            redirect('items/edit?id=' . $id);
        }

        try {
            $stmt = $db->prepare("UPDATE items SET kode = ?, nama = ?, satuan = ?, stok_minimum = ? WHERE id = ?");
            $stmt->execute([$kode, $nama, $satuan, $stok_minimum, $id]);

            Flash::set('success', 'Barang "' . e($nama) . '" berhasil diperbarui.');
            redirect('items');
        } catch (Exception $e) {
            Flash::set('error', 'Gagal memperbarui barang: ' . $e->getMessage());
            redirect('items/edit?id=' . $id);
        }
    }

    public function delete() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Csrf::validateToken($csrfToken)) {
            Flash::set('error', 'Token keamanan tidak valid.');
            redirect('items');
        }

        $id = $_POST['id'] ?? null;
        if (!$id) redirect('items');

        $db = Database::getInstance();
        try {
            $db->beginTransaction();
            // Cek apakah ada transaksi
            $stmt = $db->prepare("SELECT COUNT(*) FROM stock_transactions WHERE item_id = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) {
                // Jangan hapus permanen jika ada riwayat, cukup nonaktifkan
                $stmt = $db->prepare("UPDATE items SET is_active = 0 WHERE id = ?");
                $stmt->execute([$id]);
                Flash::set('success', 'Barang dinonaktifkan karena memiliki riwayat transaksi.');
            } else {
                // Hapus balance dulu
                $db->prepare("DELETE FROM stock_balances WHERE item_id = ?")->execute([$id]);
                $stmt = $db->prepare("DELETE FROM items WHERE id = ?");
                $stmt->execute([$id]);
                Flash::set('success', 'Barang berhasil dihapus.');
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            Flash::set('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
        redirect('items');
    }

    public function opname() {
        $db = Database::getInstance();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('items');

        $stmt = $db->prepare("SELECT i.*, sb.saldo FROM items i JOIN stock_balances sb ON i.id = sb.item_id WHERE i.id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        if (!$item) redirect('items');

        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];
        $idempotencyKey = generateIdempotencyKey();

        ob_start();
        require_once __DIR__ . '/../../views/items/opname.php';
        $content = ob_get_clean();

        $title = 'Stock Opname - ' . $item['nama'];
        require_once __DIR__ . '/../../views/layout.php';
    }

    public function processOpname() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('items');

        $id = $_POST['id'];
        $fisik = (int)$_POST['stok_fisik'];
        $sistem = (int)$_POST['stok_sistem'];
        $selisih = $fisik - $sistem;
        $idempotencyKey = $_POST['idempotency_key'];

        if ($selisih == 0) {
            Flash::set('success', 'Stok sudah sesuai, tidak ada penyesuaian yang dibuat.');
            redirect('items/history?id=' . $id);
        }

        $data = [
            'item_id' => $id,
            'jumlah' => abs($selisih),
            'arah' => $selisih > 0 ? 1 : -1,
            'tipe' => 'penyesuaian_opname',
            'tanggal' => date('Y-m-d'),
            'pihak' => 'Sistem (Opname)',
            'keterangan' => 'Penyesuaian stok opname. Fisik: ' . $fisik . ', Sistem: ' . $sistem . ', Selisih: ' . $selisih,
            'created_by' => currentUserId(),
            'idempotency_key' => $idempotencyKey
        ];

        $result = StockService::catat($data);

        if ($result['success']) {
            Flash::set('success', 'Stock opname berhasil diproses.');
        } else {
            Flash::set('error', 'Gagal memproses opname: ' . $result['message']);
        }

        redirect('items/history?id=' . $id);
    }
}
