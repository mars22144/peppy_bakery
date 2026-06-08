<?php 
include 'layouts/header.php'; 
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer'){
    header("Location: login.php"); exit;
}
?>
<section class="page-section active" style="padding-top: 100px; padding-bottom: 100px;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        <h2 style="font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 30px;">Checkout</h2>
        
        <div style="background: white; border-radius: 15px; padding: 40px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
            <form action="customer/orders.php" method="POST">
                <h3 style="margin-bottom: 20px; font-family: 'Playfair Display', serif;">Data Pengiriman</h3>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Alamat Lengkap</label>
                    <textarea required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'DM Sans', sans-serif; height: 100px;"></textarea>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nomor HP</label>
                    <input type="text" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'DM Sans', sans-serif;">
                </div>
                
                <h3 style="margin-bottom: 20px; margin-top: 40px; font-family: 'Playfair Display', serif;">Metode Pembayaran</h3>
                <div style="margin-bottom: 30px;">
                    <select required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'DM Sans', sans-serif;">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="transfer">Transfer Bank (BCA / Mandiri)</option>
                        <option value="ewallet">E-Wallet (Gopay / OVO)</option>
                        <option value="cod">Cash on Delivery (COD)</option>
                    </select>
                </div>
                
                <div style="border-top: 1px solid #eee; padding-top: 20px; text-align: right;">
                    <h3 style="margin-bottom: 20px;">Total Bayar: <span style="color: var(--primary-color);">Rp 70.000</span></h3>
                    <button type="submit" class="btn-primary ripple-btn" style="border: none; padding: 15px 40px; font-size: 1.1rem; cursor: pointer;">Buat Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php include 'layouts/footer.php'; ?>
