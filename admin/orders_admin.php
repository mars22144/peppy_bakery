<?php include 'layouts/admin_header.php'; ?>

<div class="table-container">
    <div class="table-header">
        <h2>Manajemen Pesanan</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#ORD-1001</td>
                <td>Budi Santoso</td>
                <td>20 Mei 2026</td>
                <td>Rp 70.000</td>
                <td>
                    <select class="order-status-select">
                        <option value="diproses" selected>Diproses</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </td>
                <td>
                    <button class="btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                    <button class="btn-secondary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                </td>
            </tr>
            <tr>
                <td>#ORD-1000</td>
                <td>Siti Aminah</td>
                <td>19 Mei 2026</td>
                <td>Rp 125.000</td>
                <td>
                    <select class="order-status-select">
                        <option value="diproses">Diproses</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai" selected>Selesai</option>
                    </select>
                </td>
                <td>
                    <button class="btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                    <button class="btn-secondary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include 'layouts/admin_footer.php'; ?>
