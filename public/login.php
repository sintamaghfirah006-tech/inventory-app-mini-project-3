<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../config/database.php';
if (current_user()) redirect('dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $pdo->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => (int)$user['id'], 'username' => $user['username'], 'role' => $user['role']];
        redirect('dashboard.php');
    }
    $error = 'Username atau password salah.';
}
layout_start('Login');
echo '<div class="card login"><p class="muted">Masuk untuk mengelola inventaris.</p>';
if ($error) echo '<div class="alert error">'.e($error).'</div>';
echo '<form method="post"><input type="hidden" name="csrf_token" value="'.e(csrf_token()).'"><label>Username</label><input name="username" required autocomplete="username"><label>Password</label><input type="password" name="password" required autocomplete="current-password"><p><button type="submit">Login</button></p></form><p class="muted">Demo: admin / Admin123! · staff / Staff123!</p></div>';
layout_end();
