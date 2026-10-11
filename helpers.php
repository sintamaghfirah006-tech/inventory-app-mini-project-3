<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function redirect(string $path): never {
    header('Location: ' . $path);
    exit;
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Permintaan tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void {
    if (!current_user()) redirect('login.php');
}
function require_admin(): void {
    require_login();
    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Akses ditolak: hanya admin yang dapat membuka halaman ini.');
    }
}
function flash(string $message, string $type = 'success'): void {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}
function show_flash(): void {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo '<div class="alert '.e($f['type']).'">'.e($f['message']).'</div>';
        unset($_SESSION['flash']);
    }
}
function layout_start(string $title): void {
    $user = current_user();
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>'.e($title).' - Inventory App</title><link rel="stylesheet" href="assets/style.css"></head><body>';
    echo '<header><a class="brand" href="dashboard.php">Inventory App</a><nav>';
    if ($user) {
        echo '<a href="dashboard.php">Dashboard</a><a href="categories.php">Kategori</a><a href="products.php">Produk</a><a href="suppliers.php">Supplier</a><a href="stock.php">Transaksi Stok</a><a href="history.php">Riwayat</a>';
        if ($user['role'] === 'admin') echo '<a href="users.php">Pengguna</a>';
        echo '<span class="who">'.e($user['username']).' · '.e($user['role']).'</span><a href="logout.php">Logout</a>';
    }
    echo '</nav></header><main><h1>'.e($title).'</h1>';
    show_flash();
}
function layout_end(): void { echo '</main><footer>Mini Project 3 · Inventory Management System</footer></body></html>'; }
