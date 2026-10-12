# Inventory Management System 

Aplikasi inventaris sederhana menggunakan PHP native dan MySQL untuk XAMPP.

## Persyaratan
- XAMPP (Apache + MySQL)
- PHP 8.x direkomendasikan
- Browser

## Instalasi
1. Ekstrak folder `inventory-app` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL dari XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`.
4. Import file `database/schema.sql`.
5. Periksa pengaturan database di `config/database.php` (default XAMPP: user `root`, password kosong).
6. Buka `http://localhost/inventory-app/public/`.

## Akun demo
- Admin: `admin` / `Admin123!`
- Staff: `staff` / `Staff123!`

Akun demo dibuat saat pertama kali membuka aplikasi setelah database diimpor. Jika tidak dibuat otomatis, jalankan `php database/seed.php` dari terminal pada folder proyek.

## Catatan keamanan
Akun demo hanya untuk tugas lokal. Ganti kata sandi sebelum dipasang di server publik. Aplikasi memakai prepared statements, validasi input, escaping output, CSRF token, session, dan pengecekan role dasar.
