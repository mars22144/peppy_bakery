<?php
require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../login.php'); exit;
}

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);

// batalkan pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel_order') {
    $cancel_id = (int)($_POST['order_id'] ?? 0);
    
    // Check pemilik dan status
    $cek = $pdo->prepare('SELECT status_order FROM orders WHERE id_order = ? AND id_user = ?');
    $cek->execute([$cancel_id, $_SESSION['user_id']]);
    $order_data = $cek->fetch();
    
    if ($order_data && $order_data['status_order'] === 'pending') {
        try {
            $pdo->beginTransaction();

            // jika dibatalkan stok akan kembali semula
            $items_stmt = $pdo->prepare('SELECT id_produk, qty FROM order_details WHERE id_order = ?');
            $items_stmt->execute([$cancel_id]);
            $cancel_items = $items_stmt->fetchAll();

            $restore_stmt = $pdo->prepare('UPDATE products SET stok = stok + ? WHERE id_produk = ?');
            foreach ($cancel_items as $item) {
                $restore_stmt->execute([$item['qty'], $item['id_produk']]);
            }

            $upd = $pdo->prepare("UPDATE orders SET status_order = 'dibatalkan' WHERE id_order = ?");
            $upd->execute([$cancel_id]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
        }
        
        header("Location: order_detail.php?id=$cancel_id&cancel_success=1");
        exit;
    }
}

include '../layouts/header.php';

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

        <?php if (isset($_GET['cancel_success'])): ?>
            <div style="background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:0.9rem;">
                Pesanan Anda berhasil dibatalkan.
            </div>
        <?php endif; ?>

        <!-- Info Card -->
        <div class="orders-card" style="margin-bottom:20px;padding:20px 24px;">
            <div class="order-info-grid">
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

            <?php if ($order['status_order'] === 'pending'): ?>
                <div style="margin-top:20px; padding-top:15px; border-top:1px solid #eee; text-align:right;">
                    <form action="order_detail.php?id=<?= $id ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');" style="display:inline-block;">
                        <input type="hidden" name="action" value="cancel_order">
                        <input type="hidden" name="order_id" value="<?= $id ?>">
                        <button type="submit" class="btn-dark ripple-btn" style="background:#e53935; border:none; padding:10px 20px; font-weight:600; color:#fff; border-radius:6px; cursor:pointer;">
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Items -->
        <div class="orders-card">
            <h3 style="margin-bottom:16px;font-size:1rem;color:#555;">Item Pesanan</h3>
            <div class="table-responsive">
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
    </div>
</section>

<?php include '../layouts/footer.php'; ?>
