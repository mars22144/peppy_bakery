<?php
require_once '../config/database.php';
include 'layouts/admin_header.php';

$pdo = getDB();
$msg = '';

// ── UPDATE STATUS ────────────────────────────────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id     = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $allowed = ['diproses', 'dikirim', 'selesai', 'dibatalkan'];
    if ($id && in_array($status, $allowed)) {
        $pdo->prepare('UPDATE orders SET status_order = ? WHERE id_order = ?')->execute([$status, $id]);
        $msg = 'Status pesanan berhasil diperbarui.';
    }
}

// ── FETCH ORDERS WITH PAGINATION ─────────────────────────────────────────────
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Total orders count
$total_data = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$total_pages = ceil($total_data / $limit);

$orders_stmt = $pdo->prepare(
    'SELECT o.*, u.nama AS customer_name, u.email AS customer_email
    FROM orders o
    JOIN users u ON u.id_user = o.id_user
    ORDER BY o.tgl_order DESC
    LIMIT :limit OFFSET :offset'
);
$orders_stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$orders_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$orders_stmt->execute();
$orders = $orders_stmt->fetchAll();
?>

<?php if ($msg): ?>
    <div class="admin-alert admin-alert--success"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="table-container">
    <div class="table-header">
        <h2>Manajemen Pesanan</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <?php
                $status_map = [
                    'diproses'   => 'status-pill--processing',
                    'dikirim'    => 'status-pill--shipped',
                    'selesai'    => 'status-pill--done',
                    'dibatalkan' => 'status-pill--cancelled',
                ];
                $sc = $status_map[$o['status_order']] ?? '';
            ?>
            <tr>
                <td>#ORD-<?= str_pad($o['id_order'], 5, '0', STR_PAD_LEFT) ?></td>
                <td>
                    <?= htmlspecialchars($o['customer_name']) ?>
                    <br><small style="color:#aaa;"><?= htmlspecialchars($o['customer_email']) ?></small>
                </td>
                <td><?= date('d M Y', strtotime($o['tgl_order'])) ?></td>
                <td>Rp <?= number_format($o['ttl_harga'], 0, ',', '.') ?></td>
                <td>
                    <form method="POST" style="display:inline-flex;align-items:center;gap:6px;">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="id" value="<?= $o['id_order'] ?>">
                        <select name="status" class="order-status-select">
                            <option value="diproses"   <?= $o['status_order']==='diproses'   ? 'selected':'' ?>>Diproses</option>
                            <option value="dikirim"    <?= $o['status_order']==='dikirim'    ? 'selected':'' ?>>Dikirim</option>
                            <option value="selesai"    <?= $o['status_order']==='selesai'    ? 'selected':'' ?>>Selesai</option>
                            <option value="dibatalkan" <?= $o['status_order']==='dibatalkan' ? 'selected':'' ?>>Dibatalkan</option>
                        </select>
                        <button type="submit" class="btn-primary btn-sm"><i class="fas fa-save"></i></button>
                    </form>
                </td>
                <td>
                    <a href="order_detail_admin.php?id=<?= $o['id_order'] ?>" class="btn-secondary btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?>
            <tr><td colspan="6" style="text-align:center;color:#aaa;">Belum ada pesanan.</td></tr>
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

<?php include 'layouts/admin_footer.php'; ?>
