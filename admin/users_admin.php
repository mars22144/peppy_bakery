<?php
require_once '../config/database.php';
include 'layouts/admin_header.php';

$pdo = getDB();

// Customers with order count (paginated)
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Total customers count
$total_data = $pdo->query('SELECT COUNT(*) FROM users WHERE role = "customer"')->fetchColumn();
$total_pages = ceil($total_data / $limit);

$customers_stmt = $pdo->prepare(
    'SELECT u.id_user, u.nama, u.email, u.created_at,
            COUNT(o.id_order) AS order_count
    FROM users u
    LEFT JOIN orders o ON o.id_user = u.id_user
    WHERE u.role = "customer"
    GROUP BY u.id_user
    ORDER BY u.created_at DESC
    LIMIT :limit OFFSET :offset'
);
$customers_stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$customers_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$customers_stmt->execute();
$customers = $customers_stmt->fetchAll();
?>

<div class="table-container">
    <div class="table-header">
        <h2>Daftar Pelanggan</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Bergabung</th>
                <th>Total Pesanan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $c): ?>
            <tr>
                <td>USR-<?= str_pad($c['id_user'], 3, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($c['nama']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                <td><?= $c['order_count'] ?> Pesanan</td>
                <td><span class="status-pill status-pill--active">Aktif</span></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($customers)): ?>
            <tr><td colspan="6" style="text-align:center;color:#aaa;">Belum ada pelanggan.</td></tr>
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
