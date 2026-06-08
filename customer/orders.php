<?php
include '../layouts/header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php"); exit;
}
?>

<section class="page-section active orders-section">
    <div class="page-medium">
        <div class="orders-header">
            <h2>Pesanan Saya</h2>
        </div>

        <div class="orders-card">
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
                    <tr>
                        <td class="td-id">#ORD-1001</td>
                        <td class="td-date">20 Mei 2026</td>
                        <td class="td-total">Rp 70.000</td>
                        <td><span class="status-badge status-badge--processing">Diproses</span></td>
                        <td><a href="#" class="orders-detail-link">Detail</a></td>
                    </tr>
                    <tr>
                        <td class="td-id">#ORD-0995</td>
                        <td class="td-date">15 Mei 2026</td>
                        <td class="td-total">Rp 125.000</td>
                        <td><span class="status-badge status-badge--done">Selesai</span></td>
                        <td><a href="#" class="orders-detail-link">Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include '../layouts/footer.php'; ?>
