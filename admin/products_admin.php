<?php
require_once '../config/database.php';
include 'layouts/admin_header.php';

$pdo = getDB();
$msg = '';

// ── DELETE ──────────────────────────────────────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    $pdo->prepare('DELETE FROM products WHERE id_produk = ?')->execute([$id]);
    $msg = 'Produk berhasil dihapus.';
}

// ── CREATE ───────────────────────────────────────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $stock       = (int)($_POST['stock'] ?? 0);
    $image_url   = trim($_POST['image_url'] ?? '');
    $berat       = trim($_POST['berat'] ?? '');
    $ketahanan   = trim($_POST['ketahanan'] ?? '');

    // Handle file upload
    if (!empty($_FILES['image_file']['name'])) {
        $upload_dir = dirname(__DIR__) . '/assets/img/products/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $ext       = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $filename  = 'prod_' . time() . '_' . mt_rand(100,999) . '.' . $ext;
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_dir . $filename)) {
            $image_url = '/peppy_bakery/assets/img/products/' . $filename;
        }
    }

    $pdo->prepare('INSERT INTO products (nama_produk, deskripsi, harga, stok, foto, berat, ketahanan) VALUES (?,?,?,?,?,?,?)')
        ->execute([$name, $description, $price, $stock, $image_url, $berat, $ketahanan]);
    $msg = 'Produk berhasil ditambahkan.';
}

// ── UPDATE ───────────────────────────────────────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id          = (int)($_POST['id'] ?? 0);
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $stock       = (int)($_POST['stock'] ?? 0);
    $image_url   = trim($_POST['image_url'] ?? '');
    $berat       = trim($_POST['berat'] ?? '');
    $ketahanan   = trim($_POST['ketahanan'] ?? '');

    // Handle file upload
    if (!empty($_FILES['image_file']['name'])) {
        $upload_dir = dirname(__DIR__) . '/assets/img/products/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $ext      = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $filename = 'prod_' . time() . '_' . mt_rand(100,999) . '.' . $ext;
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_dir . $filename)) {
            $image_url = '/peppy_bakery/assets/img/products/' . $filename;
        }
    }

    if ($image_url) {
        $pdo->prepare('UPDATE products SET nama_produk=?, deskripsi=?, harga=?, stok=?, foto=?, berat=?, ketahanan=? WHERE id_produk=?')
            ->execute([$name, $description, $price, $stock, $image_url, $berat, $ketahanan, $id]);
    } else {
        $pdo->prepare('UPDATE products SET nama_produk=?, deskripsi=?, harga=?, stok=?, berat=?, ketahanan=? WHERE id_produk=?')
            ->execute([$name, $description, $price, $stock, $berat, $ketahanan, $id]);
    }
    $msg = 'Produk berhasil diperbarui.';
}

// ── FETCH ALL WITH PAGINATION ────────────────────────────────────────────────
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Total products count
$total_data = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$total_pages = ceil($total_data / $limit);

$products_stmt = $pdo->prepare('SELECT * FROM products ORDER BY id_produk DESC LIMIT :limit OFFSET :offset');
$products_stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$products_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$products_stmt->execute();
$products = $products_stmt->fetchAll();
?>

<?php if ($msg): ?>
    <div class="admin-alert admin-alert--success"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="table-container">
    <div class="table-header">
        <h2>Manajemen Produk</h2>
        <button class="btn-primary" onclick="openModal('modal-create')">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <?php
                if ($p['stok'] == 0)  $status_class = 'status-pill--unavailable';
                elseif ($p['stok'] <= 5) $status_class = 'status-pill--low-stock';
                else $status_class = 'status-pill--available';

                if ($p['stok'] == 0)  $status_label = 'Habis';
                elseif ($p['stok'] <= 5) $status_label = 'Stok Menipis';
                else $status_label = 'Tersedia';
            ?>
            <tr>
                <td>
                    <img src="<?= htmlspecialchars($p['foto'] ?: '/peppy_bakery/assets/img/no-image.png') ?>"
                        class="table-thumb" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                </td>
                <td>
                    <?= htmlspecialchars($p['nama_produk']) ?>
                </td>
                <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                <td><?= $p['stok'] ?></td>
                <td><span class="status-pill <?= $status_class ?>"><?= $status_label ?></span></td>
                <td>
                    <button class="btn-primary btn-sm btn-warning"
                        onclick='openEditModal(<?= json_encode($p) ?>)'>
                        <i class="fas fa-edit"></i>
                    </button>
                    <form method="POST" style="display:inline;"
                        onsubmit="return confirm('Hapus produk ini?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $p['id_produk'] ?>">
                        <button type="submit" class="btn-primary btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
            <tr><td colspan="6" style="text-align:center;color:#aaa;">Belum ada produk.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="pagination-link">&laquo; Prev</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="pagination-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>" class="pagination-link">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ── Modal: CREATE ──────────────────────────────────────────────────────── -->
<div id="modal-create" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Tambah Produk Baru</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data" class="modal-form">
            <input type="hidden" name="action" value="create">
            <div class="modal-form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" required class="auth-input" placeholder="Nama produk">
            </div>
            <div class="modal-form-group">
                <label>Deskripsi</label>
                <textarea name="description" class="auth-input modal-textarea" placeholder="Deskripsi produk"></textarea>
            </div>
            <div class="modal-row">
                <div class="modal-form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" required min="0" class="auth-input" placeholder="35000">
                </div>
                <div class="modal-form-group">
                    <label>Stok</label>
                    <input type="number" name="stock" required min="0" class="auth-input" placeholder="10">
                </div>
            </div>
            <div class="modal-row">
                <div class="modal-form-group">
                    <label>Berat (dalam satuan gram)</label>
                    <input type="text" name="berat" class="auth-input" placeholder="250">
                </div>
                <div class="modal-form-group">
                    <label>Ketahanan (misal: 3 hari)</label>
                    <input type="text" name="ketahanan" class="auth-input" placeholder="3 hari pada suhu ruang">
                </div>
            </div>
            <div class="modal-form-group">
                <label>Upload Gambar</label>
                <input type="file" name="image_file" accept="image/*" class="auth-input">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Batal</button>
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ── Modal: EDIT ────────────────────────────────────────────────────────── -->
<div id="modal-edit" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Produk</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data" class="modal-form">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" id="edit-name" required class="auth-input">
            </div>
            <div class="modal-form-group">
                <label>Deskripsi</label>
                <textarea name="description" id="edit-description" class="auth-input modal-textarea"></textarea>
            </div>
            <div class="modal-row">
                <div class="modal-form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" id="edit-price" required min="0" class="auth-input">
                </div>
                <div class="modal-form-group">
                    <label>Stok</label>
                    <input type="number" name="stock" id="edit-stock" required min="0" class="auth-input">
                </div>
            </div>
            <div class="modal-row">
                <div class="modal-form-group">
                    <label>Berat</label>
                    <input type="text" name="berat" id="edit-berat" class="auth-input">
                </div>
                <div class="modal-form-group">
                    <label>Ketahanan</label>
                    <input type="text" name="ketahanan" id="edit-ketahanan" class="auth-input">
                </div>
            </div>
            <div class="modal-form-group">
                <label>URL Gambar (kosongkan jika tidak berubah)</label>
                <input type="text" name="image_url" id="edit-image-url" class="auth-input">
            </div>
            <div class="modal-form-group">
                <label>Upload Gambar Baru</label>
                <input type="file" name="image_file" accept="image/*" class="auth-input">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit')">Batal</button>
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function openEditModal(p) {
    document.getElementById('edit-id').value          = p.id_produk;
    document.getElementById('edit-name').value        = p.nama_produk;
    document.getElementById('edit-description').value = p.deskripsi;
    document.getElementById('edit-price').value       = p.harga;
    document.getElementById('edit-stock').value       = p.stok;
    document.getElementById('edit-image-url').value   = p.foto || '';
    document.getElementById('edit-berat').value       = p.berat || '';
    document.getElementById('edit-ketahanan').value   = p.ketahanan || '';
    openModal('modal-edit');
}
// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (e.target === el) el.style.display = 'none';
    });
});
</script>

<?php include 'layouts/admin_footer.php'; ?>
