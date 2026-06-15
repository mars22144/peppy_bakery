<?php
require_once '../config/database.php';
include '../layouts/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../login.php'); exit;
}

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);

// Fetch order with shipping and payment — must belong to this user
$stmt = $pdo->prepare(
    'SELECT o.*, 
            s.alamat_kirim, s.kurir, s.no_resi, s.status_kirim,
            p.metode_nayar, p.status_bayar
    FROM orders o 
    LEFT JOIN shipping s ON s.id_order = o.id_order
    LEFT JOIN payments p ON p.id_order = o.id_order
    WHERE o.id_order = ? AND o.id_user = ? LIMIT 1'
);
$stmt->execute([$id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php'); exit;
}

// Fetch details
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
    'pending'    => 'status-badge--pending',
    'diproses'   => 'status-badge--processing',
    'dikirim'    => 'status-badge--shipped',
    'selesai'    => 'status-badge--done',
    'dibatalkan' => 'status-badge--cancelled',
];
?>

<section class="page-section active orders-section">
    <div class="page-medium">
        <div class="orders-header" style="margin-bottom:20px;">
            <a href="orders.php" class="orders-detail-link" style="font-size:0.9rem;">← Kembali ke Pesanan</a>
            <h2 style="margin-top:10px;">Detail Pesanan #ORD-<?= str_pad($order['id_order'], 4, '0', STR_PAD_LEFT) ?></h2>
        </div>

        <!-- Info Card -->
        <div class="orders-card" style="margin-bottom:20px;padding:20px 24px;">
            <div style="display:flex;justify-content:space-around;gap:50px;">
                <div>
                    <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:4px;text-transform:uppercase;">Tanggal Pesanan</label>
                    <p style="font-weight:600;"><?= date('d M Y, H:i', strtotime($order['tgl_order'])) ?></p>
                    
                    <label style="color:#888;font-size:0.8rem;display:block;margin-top:16px;margin-bottom:4px;text-transform:uppercase;">Status Order</label>
                    <span class="status-badge <?= $status_class[$order['status_order']] ?? '' ?>">
                        <?= $status_label[$order['status_order']] ?? $order['status_order'] ?>
                    </span>
                </div>
                <div>
                    <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:4px;text-transform:uppercase;">Alamat Pengiriman</label>
                    <p><?= nl2br(htmlspecialchars($order['alamat_kirim'])) ?></p>
                    
                    <label style="color:#888;font-size:0.8rem;display:block;margin-top:16px;margin-bottom:4px;text-transform:uppercase;">Pembayaran</label>
                    <p><strong><?= htmlspecialchars(strtoupper($order['metode_nayar'])) ?></strong> (<?= strtoupper($order['status_bayar']) ?>)</p>
                </div>

                <div>
                    <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:4px;text-transform:uppercase;">Kurir</label>
                    <p><strong><?= htmlspecialchars(strtoupper($order['kurir'])) ?></strong></p>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="orders-card">
            <h3 style="margin-bottom:16px;font-size:1rem;color:#555;">Item Pesanan</h3>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td style="display:flex;align-items:center;gap:10px;">
                            <img src="<?= htmlspecialchars($item['foto'] ?: 'https://via.placeholder.com/50') ?>"
                                style="width:44px;height:44px;object-fit:cover;border-radius:6px;" alt="">
                            <span><?= htmlspecialchars($item['nama_produk']) ?></span>
                        </td>
                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>Rp <?= number_format($item['sub_total'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;font-weight:700;padding:14px 15px;">Total</td>
                        <td style="font-weight:700;color:#e07b39;">Rp <?= number_format($order['ttl_harga'], 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>
    </div>
</section>

<?php include '../layouts/footer.php'; ?>
