<?php
require_once __DIR__ . '/../helpers.php'; require_once __DIR__ . '/../config/database.php'; require_login();
if ($_SERVER['REQUEST_METHOD']==='POST') { verify_csrf(); $action=$_POST['action']??'';
 try {
  if($action==='save'){ $id=(int)($_POST['id']??0); $name=trim((string)($_POST['name']??'')); $desc=trim((string)($_POST['description']??'')); if($name==='') throw new RuntimeException('Nama kategori wajib diisi.');
   if($id){$s=$pdo->prepare('UPDATE categories SET name=?,description=? WHERE id=?');$s->execute([$name,$desc?:null,$id]);}else{$s=$pdo->prepare('INSERT INTO categories(name,description) VALUES(?,?)');$s->execute([$name,$desc?:null]);} flash('Kategori berhasil disimpan.');
  } elseif($action==='delete'){ require_admin(); $s=$pdo->prepare('DELETE FROM categories WHERE id=?');$s->execute([(int)$_POST['id']]);flash('Kategori dihapus.');}
 } catch(Throwable $e){flash($e instanceof PDOException?'Kategori tidak dapat disimpan/dihapus. Periksa nama duplikat atau produk terkait.':$e->getMessage(),'error');}
 redirect('categories.php');
}
$edit=null;if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM categories WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch();}
$rows=$pdo->query('SELECT c.*,COUNT(p.id) product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name')->fetchAll();
layout_start('Kategori');
echo '<div class="card"><h2>'.($edit?'Edit Kategori':'Tambah Kategori').'</h2><form method="post"><input type="hidden" name="csrf_token" value="'.e(csrf_token()).'"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="'.(int)($edit['id']??0).'"><label>Nama kategori</label><input name="name" maxlength="100" required value="'.e($edit['name']??'').'"><label>Deskripsi</label><input name="description" maxlength="255" value="'.e($edit['description']??'').'"><p><button>Simpan</button> <a class="btn secondary" href="categories.php">Batal</a></p></form></div><div class="card table-wrap"><table><tr><th>Nama</th><th>Deskripsi</th><th>Jumlah produk</th><th>Aksi</th></tr>';
foreach($rows as $r){echo '<tr><td>'.e($r['name']).'</td><td>'.e($r['description']).'</td><td>'.(int)$r['product_count'].'</td><td><a class="btn secondary" href="?edit='.(int)$r['id'].'">Edit</a> ';if(current_user()['role']==='admin')echo '<form method="post" onsubmit="return confirm(\'Hapus kategori ini?\')"><input type="hidden" name="csrf_token" value="'.e(csrf_token()).'"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="'.(int)$r['id'].'"><button class="danger">Hapus</button></form>';echo '</td></tr>';}
echo '</table></div>';layout_end();
