<?php
require_once __DIR__ . '/../helpers.php'; require_once __DIR__ . '/../config/database.php'; require_login();
$products = (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$categories = (int)$pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$stock = (int)$pdo->query('SELECT COALESCE(SUM(quantity),0) FROM products')->fetchColumn();
$value = (float)$pdo->query('SELECT COALESCE(SUM(price*quantity),0) FROM products')->fetchColumn();
$recent = $pdo->query("SELECT t.created_at,t.type,t.quantity,t.note,p.name AS product,u.username FROM stock_transactions t JOIN products p ON p.id=t.product_id JOIN users u ON u.id=t.user_id ORDER BY t.id DESC LIMIT 8")->fetchAll();
layout_start('Dashboard');
echo '<div class="stats"><div class="card stat"><span>Total Produk</span><strong>'.$products.'</strong></div><div class="card stat"><span>Kategori</span><strong>'.$categories.'</strong></div><div class="card stat"><span>Total Unit Stok</span><strong>'.$stock.'</strong></div><div class="card stat"><span>Nilai Persediaan</span><strong>Rp '.number_format($value,0,',','.').'</strong></div></div>';
echo '<div class="card"><h2>Aktivitas Stok Terbaru</h2><div class="table-wrap"><table><tr><th>Waktu</th><th>Produk</th><th>Jenis</th><th>Jumlah</th><th>Pengguna</th><th>Catatan</th></tr>';
foreach($recent as $r) echo '<tr><td>'.e($r['created_at']).'</td><td>'.e($r['product']).'</td><td>'.($r['type']==='in'?'Masuk':'Keluar').'</td><td>'.(int)$r['quantity'].'</td><td>'.e($r['username']).'</td><td>'.e($r['note']).'</td></tr>';
if(!$recent) echo '<tr><td colspan="6">Belum ada transaksi stok.</td></tr>';
echo '</table></div></div><p><a class="btn" href="products.php">Kelola Produk</a> <a class="btn secondary" href="stock.php">Catat Transaksi</a></p>';
layout_end();
