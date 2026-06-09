<?php
require_once '../config/database.php';
include 'layouts/admin_header.php';

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);

// Fetch order with customer, shipping, and payment info
$stmt = $pdo->prepare(
    'SELECT o.*, u.nama AS customer_name, u.email AS customer_email, u.no_hp AS customer_phone,
            s.alamat_kirim, s.kurir, s.no_resi, s.status_kirim,
            p.metode_nayar, p.status_bayar, p.bukti_bayar
    FROM orders o 
    JOIN users u ON u.id_user = o.id_user
    LEFT JOIN shipping s ON s.id_order = o.id_order
    LEFT JOIN payments p ON p.id_order = o.id_order
    WHERE o.id_order = ? LIMIT 1'
);
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    echo '<div class="admin-alert admin-alert--error">Pesanan tidak ditemukan.</div>';
    include 'layouts/admin_footer.php';
    exit;
}

// Fetch order details
$items = $pdo->prepare(
    'SELECT od.*, p.foto FROM order_details od
    LEFT JOIN products p ON p.id_produk = od.id_produk
    WHERE od.id_order = ?'
);
$items->execute([$id]);
$items = $items->fetchAll();

$status_label = [
    'pending'    => 'Pending',
    'diproses'   => 'Diproses',
    'dikirim'    => 'Dikirim',
    'selesai'    => 'Selesai',
    'dibatalkan' => 'Dibatalkan',
];
$status_class = [
    'pending'    => 'status-pill--warning',
    'diproses'   => 'status-pill--processing',
    'dikirim'    => 'status-pill--shipped',
    'selesai'    => 'status-pill--done',
    'dibatalkan' => 'status-pill--cancelled',
];
?>

<div class="header-place" style="margin-bottom:20px;">
    <a href="orders_admin.php" class="btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    <h2 style="margin-top:12px;">Detail Pesanan #ORD-<?= str_pad($order['id_order'], 4, '0', STR_PAD_LEFT) ?></h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
    <!-- Info Pelanggan & Pengiriman -->
    <div class="table-container" style="margin-bottom:0;">
        <div class="table-header">
            <h3>Info Pelanggan & Pengiriman</h3>
        </div>
        <div style="padding:16px 20px;line-height:1.8;">
            <p><strong>Nama:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
            <p><strong>No. HP:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
            <hr style="margin:10px 0;border:0;border-top:1px solid #eee;">
            <p><strong>Alamat Kirim:</strong> <?= nl2br(htmlspecialchars($order['alamat_kirim'])) ?></p>
            <p><strong>Kurir:</strong> <?= htmlspecialchars($order['kurir'] ?: '-') ?></p>
            <p><strong>No. Resi:</strong> <?= htmlspecialchars($order['no_resi'] ?: '-') ?></p>
            <p><strong>Status Kirim:</strong> <span class="status-pill"><?= strtoupper($order['status_kirim'] ?: 'pending') ?></span></p>
        </div>
    </div>
    <!-- Info Pesanan & Pembayaran -->
    <div class="table-container" style="margin-bottom:0;">
        <div class="table-header">
            <h3>Info Pesanan & Pembayaran</h3>
        </div>
        <div style="padding:16px 20px;line-height:1.8;">
            <p><strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($order['tgl_order'])) ?></p>
            <p><strong>Metode Bayar:</strong> <?= htmlspecialchars(strtoupper($order['metode_nayar'])) ?></p>
            <p><strong>Status Bayar:</strong> <span class="status-pill"><?= strtoupper($order['status_bayar'] ?: 'unpaid') ?></span></p>
            <?php if ($order['bukti_bayar']): ?>
                <p><strong>Bukti:</strong> <a href="<?= htmlspecialchars($order['bukti_bayar']) ?>" target="_blank">Lihat Bukti</a></p>
            <?php endif; ?>
            <hr style="margin:10px 0;border:0;border-top:1px solid #eee;">
            <p><strong>Status Order:</strong>
                <span class="status-pill <?= $status_class[$order['status_order']] ?? '' ?>">
                    <?= $status_label[$order['status_order']] ?? $order['status_order'] ?>
                </span>
            </p>
            <p><strong>Total:</strong> <span style="color:#e07b39;font-weight:700;">Rp <?= number_format($order['ttl_harga'], 0, ',', '.') ?></span></p>
        </div>
    </div>
</div>

<!-- Order Items -->
<div class="table-container">
    <div class="table-header">
        <h3>Item Pesanan</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Produk</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($item['foto'] ?: 'https://via.placeholder.com/50') ?>"
                            class="table-thumb" alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                    </td>
                    <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td>Rp <?= number_format($item['sub_total'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;font-weight:700;padding:12px 16px;">Total</td>
                <td style="font-weight:700;color:#e07b39;">Rp <?= number_format($order['ttl_harga'], 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php include 'layouts/admin_footer.php'; ?>