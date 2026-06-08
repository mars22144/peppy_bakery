<?php include 'layouts/admin_header.php'; ?>

<div class="table-container">
    <div class="table-header">
        <h2>Daftar Pelanggan</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID User</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Total Pesanan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>USR-001</td>
                <td>Budi Santoso</td>
                <td>budi@example.com</td>
                <td>5 Pesanan</td>
                <td><span class="status-pill status-pill--active">Aktif</span></td>
            </tr>
            <tr>
                <td>USR-002</td>
                <td>Siti Aminah</td>
                <td>siti@example.com</td>
                <td>2 Pesanan</td>
                <td><span class="status-pill status-pill--active">Aktif</span></td>
            </tr>
        </tbody>
    </table>
</div>

<?php include 'layouts/admin_footer.php'; ?>
