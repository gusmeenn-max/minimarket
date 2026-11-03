# Minimarket — Sistem Informasi Persediaan Barang

Aplikasi web (Laravel 12 + MySQL) untuk mengelola stok minimarket: master kategori & barang,
transaksi barang masuk/keluar, dashboard, laporan (Excel/PDF/cetak), dan manajemen pengguna.

## Menjalankan
1. `composer install` (jika folder `vendor` belum ada)
2. Salin `.env.example` ke `.env`, lalu atur database (`DB_DATABASE`, dst.) dan jalankan `php artisan key:generate`
3. `php artisan migrate:fresh --seed`
4. `php artisan serve` → buka http://127.0.0.1:8000

Akun awal: **admin@gmail.com** / **admin123** (segera ganti lewat menu Ubah Password).

## Frontend
Seluruh aset (Bootstrap 5, Bootstrap Icons, DataTables, Chart.js, font) tersimpan lokal di `public/assets`,
sehingga aplikasi berjalan tanpa koneksi internet. Gaya kustom ada di `public/assets/css/minimarket.css`.
