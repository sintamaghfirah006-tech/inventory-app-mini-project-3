<?php
require_once __DIR__ . '/../config/database.php';
$accounts = [
    ['admin', 'Admin123!', 'admin'],
    ['staff', 'Staff123!', 'staff'],
];
$stmt = $pdo->prepare('INSERT IGNORE INTO users (username, password_hash, role) VALUES (?, ?, ?)');
foreach ($accounts as [$username, $password, $role]) {
    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role]);
}
echo "Akun demo siap dibuat (akun yang sudah ada tidak ditimpa).\n";
