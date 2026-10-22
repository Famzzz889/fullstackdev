# Sistem Manajemen Gudang ATK

Sistem manajemen inventaris alat tulis kantor (ATK) sederhana dengan fokus pada integritas data (anti-dobel transaksi) dan pelaporan kartu stok.

## Fitur Utama
- **Dashboard**: Ringkasan stok rendah, aktivitas terbaru, dan statistik bulanan.
- **Master Barang**: Pengelolaan data barang dengan batas stok minimum.
- **Transaksi Masuk/Keluar**: Pencatatan mutasi barang dengan perlindungan stok negatif.
- **Anti-Double Transaction**: Mencegah input ganda akibat klik cepat atau *network retry*.
- **Koreksi Transaksi**: Membatalkan transaksi salah dengan mencatat transaksi penyeimbang (*ledger style*).
- **Kartu Stok**: Laporan mutasi bulanan per barang dengan saldo berjalan.
- **Stock Opname**: Penyesuaian stok sistem dengan hasil hitung fisik.
- **Cek Konsistensi**: Validasi otomatis antara saldo snapshot dan akumulasi riwayat transaksi.

## Cara Menjalankan (XAMPP)
1. Pastikan **Apache** dan **MySQL** di XAMPP sudah berjalan.
2. Clone/Copy folder proyek ini ke dalam `C:\xampp\htdocs\Test-FullstackDev`.
3. Buat database baru bernama `inventory_at` di phpMyAdmin.
4. Import file [schema.sql](database/schema.sql) lalu [seed.sql](database/seed.sql).
5. Buka browser dan akses: `http://localhost/Test-FullstackDev/`
6. Akun Uji Coba:
   - Email: `admin@atk.test`
   - Password: `password123`

## Penjelasan Teknis: Pencegahan Data Dobel
Sistem ini mengimplementasikan pola "Anti-dobel" menggunakan kombinasi:
1. **Idempotency Key**: Setiap form menyertakan kunci unik (UUID/Hex) yang hanya berlaku untuk satu aksi.
2. **Database Unique Constraint**: Kolom `idempotency_key` pada tabel transaksi bersifat UNIQUE.
3. **Database Transaction (ACID)**: Memastikan proses pengecekan, penulisan riwayat, dan update saldo terjadi secara utuh atau tidak sama sekali.
4. **Row Level Locking (FOR UPDATE)**: Mengunci baris saldo barang saat transaksi diproses untuk mencegah *race condition* jika dua transaksi barang yang sama masuk di milidetik yang sama.

## Menjalankan Uji Konkurensi
Anda dapat memverifikasi mekanisme proteksi melalui CLI:
```bash
php tests/concurrency_test.php
```

## Keamanan
- **XSS Protection**: Semua output variabel menggunakan helper `e()` (htmlspecialchars).
- **CSRF Protection**: Semua form POST dilindungi dengan token CSRF.
- **SQL Injection**: Menggunakan PDO dengan *prepared statements* dan parameter binding.
- **Authentication**: Semua route (kecuali login) dilindungi pengecekan sesi.

## Estimasi Pengerjaan
Total waktu pengerjaan: ~10-12 jam kerja.
