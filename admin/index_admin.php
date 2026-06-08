<?php include 'layouts/admin_header.php'; ?>

<h2 style="margin-bottom: 20px;">Dashboard Overview</h2>

<div class="card-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
        <div class="stat-info">
            <h4>Total Pesanan</h4>
            <h2>1,284</h2>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-box"></i></div>
        <div class="stat-info">
            <h4>Total Produk</h4>
            <h2>24</h2>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h4>Pelanggan Aktif</h4>
            <h2>452</h2>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-wallet"></i></div>
        <div class="stat-info">
            <h4>Pendapatan (Bulan ini)</h4>
            <h2>Rp 12.5M</h2>
        </div>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <h3>Pesanan Terbaru</h3>
        <a href="#" class="btn-primary">Lihat Semua</a>
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
            <tr>
                <td>#ORD-1001</td>
                <td>Budi Santoso</td>
                <td>20 Mei 2026</td>
                <td>Rp 70.000</td>
                <td><span style="background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">Diproses</span></td>
            </tr>
            <tr>
                <td>#ORD-1000</td>
                <td>Siti Aminah</td>
                <td>19 Mei 2026</td>
                <td>Rp 125.000</td>
                <td><span style="background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">Selesai</span></td>
            </tr>
        </tbody>
    </table>
</div>

<?php include 'layouts/admin_footer.php'; ?>
