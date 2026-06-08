<?php 
include '../layouts/header.php'; 
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer'){
    header("Location: ../login.php"); exit;
}
?>
<section class="page-section active" style="padding-top: 100px; padding-bottom: 100px;">
    <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="font-family: 'Playfair Display', serif; color: var(--text-dark);">Pesanan Saya</h2>
        </div>
        
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 600px;">
                <thead>
                    <tr style="border-bottom: 2px solid #eee;">
                        <th style="padding: 15px 10px;">ID Pesanan</th>
                        <th style="padding: 15px 10px;">Tanggal</th>
                        <th style="padding: 15px 10px;">Total</th>
                        <th style="padding: 15px 10px;">Status</th>
                        <th style="padding: 15px 10px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px 10px; font-weight: 500;">#ORD-1001</td>
                        <td style="padding: 15px 10px; color: #666;">20 Mei 2026</td>
                        <td style="padding: 15px 10px; font-weight: 600;">Rp 70.000</td>
                        <td style="padding: 15px 10px;"><span style="background: #fff3cd; color: #856404; padding: 5px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Diproses</span></td>
                        <td style="padding: 15px 10px;"><a href="#" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Detail</a></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px 10px; font-weight: 500;">#ORD-0995</td>
                        <td style="padding: 15px 10px; color: #666;">15 Mei 2026</td>
                        <td style="padding: 15px 10px; font-weight: 600;">Rp 125.000</td>
                        <td style="padding: 15px 10px;"><span style="background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Selesai</span></td>
                        <td style="padding: 15px 10px;"><a href="#" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php include '../layouts/footer.php'; ?>
