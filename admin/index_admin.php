<?php
require_once '../config/database.php';
include 'layouts/admin_header.php';

$pdo = getDB();

// Stats
$total_orders   = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$total_products = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$total_customers = $pdo->query('SELECT COUNT(*) FROM users WHERE role = "customer"')->fetchColumn();
$revenue_month  = $pdo->query(
    'SELECT COALESCE(SUM(ttl_harga),0) FROM orders
    WHERE MONTH(tgl_order) = MONTH(CURDATE())
    AND YEAR(tgl_order) = YEAR(CURDATE())
    AND status_order != "dibatalkan"'
)->fetchColumn();

// Recent orders
$recent = $pdo->query(
    'SELECT o.*, u.nama AS customer_name FROM orders o
    JOIN users u ON u.id_user = o.id_user
    ORDER BY o.tgl_order DESC LIMIT 5'
)->fetchAll();

function fmt_rupiah($n) {
    if ($n >= 1000000) return 'Rp ' . number_format($n / 1000000, 1) . 'Jt';
    if ($n >= 1000)    return 'Rp ' . number_format($n / 1000, 0) . 'rb';
    return 'Rp ' . number_format($n, 0, ',', '.');
}
?>

<div class="header-place">
    <h2 class="header-dashboard">Dashboard Overview</h2>
</div>

<div class="card-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
        <div class="stat-info"><h4>Total Pesanan</h4><h2><?= number_format($total_orders) ?></h2></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-box"></i></div>
        <div class="stat-info"><h4>Total Produk</h4><h2><?= number_format($total_products) ?></h2></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info"><h4>Pelanggan Aktif</h4><h2><?= number_format($total_customers) ?></h2></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-credit-card"></i></div>
        <div class="stat-info"><h4>Pendapatan (Bulan ini)</h4><h2><?= fmt_rupiah($revenue_month) ?></h2></div>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <h3>Pesanan Terbaru</h3>
        <a href="/peppy_bakery/admin/orders_admin.php" class="btn-primary">Lihat Semua</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sc_map = [
                'pending'    => 'status-pill--warning',
                'diproses'   => 'status-pill--processing',
                'dikirim'    => 'status-pill--shipped',
                'selesai'    => 'status-pill--done',
                'dibatalkan' => 'status-pill--cancelled',
            ];
            $sl_map = [
                'pending'    => 'Pending',
                'diproses'   => 'Diproses',
                'dikirim'    => 'Dikirim',
                'selesai'    => 'Selesai',
                'dibatalkan' => 'Dibatalkan'
            ];
            foreach ($recent as $o):
            ?>
            <tr>
                <td>#ORD-<?= str_pad($o['id_order'], 4, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($o['customer_name']) ?></td>
                <td><?= date('d M Y', strtotime($o['tgl_order'])) ?></td>
                <td>Rp <?= number_format($o['ttl_harga'], 0, ',', '.') ?></td>
                <td><span class="status-pill <?= $sc_map[$o['status_order']] ?? '' ?>"><?= $sl_map[$o['status_order']] ?? $o['status_order'] ?></span></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($recent)): ?>
            <tr><td colspan="5" style="text-align:center;color:#aaa;">Belum ada pesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'layouts/admin_footer.php'; ?>
