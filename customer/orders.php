<?php
require_once '../config/database.php';
include '../layouts/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../login.php');
    exit;
}

$pdo     = getDB();
$success = isset($_GET['success']);

// ── FETCH ORDERS WITH PAGINATION ─────────────────────────────────────────────
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Total orders count
$total_data = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$total_pages = ceil($total_data / $limit);

// Fetch orders for this user
$stmt = $pdo->prepare(
    'SELECT * FROM orders WHERE id_user = ? ORDER BY tgl_order DESC'
);
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

$status_label = [
    'pending'    => 'Pending',
    'diproses'   => 'Diproses',
    'dikirim'    => 'Dikirim',
    'selesai'    => 'Selesai',
    'dibatalkan' => 'Dibatalkan',
];
$status_class = [
    'pending'    => 'status-badge--pending',
    'diproses'   => 'status-badge--processing',
    'dikirim'    => 'status-badge--shipped',
    'selesai'    => 'status-badge--done',
    'dibatalkan' => 'status-badge--cancelled',
];
?>

<section class="page-section active orders-section">
    <div class="page-medium">
        <div class="orders-header">
            <h2>Pesanan Saya</h2>
        </div>

        <?php if ($success): ?>
            <div class="auth-alert auth-alert--success" style="margin-bottom:20px;">
                Pesanan kamu berhasil dibuat! Kami akan segera memproses pesananmu.
            </div>
        <?php endif; ?>

        <div class="orders-card">
            <?php if (empty($orders)): ?>
                <div style="text-align:center;padding:60px 20px;color:#888;">
                    <p>Kamu belum memiliki pesanan.</p>
                    <a href="../products.php" class="btn-primary ripple-btn" style="display:inline-block;margin-top:16px;">Belanja Sekarang</a>
                </div>
            <?php else: ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td class="td-id">#ORD-<?= str_pad($o['id_order'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td class="td-date"><?= date('d M Y', strtotime($o['tgl_order'])) ?></td>
                                <td class="td-total">Rp <?= number_format($o['ttl_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="status-badge <?= $status_class[$o['status_order']] ?? '' ?>">
                                        <?= $status_label[$o['status_order']] ?? $o['status_order'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="order_detail.php?id=<?= $o['id_order'] ?>" class="orders-detail-link">Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include '../layouts/footer.php'; ?>