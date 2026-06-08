<?php include 'layouts/admin_header.php'; ?>

<div class="table-container">
    <div class="table-header">
        <h2>Manajemen Produk</h2>
        <a href="#" class="btn-primary"><i class="fas fa-plus"></i> Tambah Produk</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=50&q=80" class="table-thumb" alt="Croissant">
                </td>
                <td>Classic French Croissant</td>
                <td>Rp 35.000</td>
                <td>24</td>
                <td><span class="status-pill status-pill--available">Tersedia</span></td>
                <td>
                    <button class="btn-primary btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                    <button class="btn-primary btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=50&q=80" class="table-thumb" alt="Sourdough">
                </td>
                <td>Rustic Sourdough Loaf</td>
                <td>Rp 65.000</td>
                <td>5</td>
                <td><span class="status-pill status-pill--low-stock">Stok Menipis</span></td>
                <td>
                    <button class="btn-primary btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                    <button class="btn-primary btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include 'layouts/admin_footer.php'; ?>
