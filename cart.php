<?php 
include 'layouts/header.php'; 
// Protect route
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer'){
    echo "<script>alert('Silakan login terlebih dahulu'); window.location='login.php';</script>";
    exit;
}
?>
<section class="page-section active" style="padding-top: 100px; padding-bottom: 100px;">
    <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        <h2 style="font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 30px;">Keranjang Belanja</h2>
        
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
            <!-- Cart Item -->
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=100&q=80" style="width: 80px; height: 80px; border-radius: 10px; object-fit: cover;">
                    <div>
                        <h4 style="font-family: 'Playfair Display', serif; font-size: 1.2rem; margin-bottom: 5px;">Classic French Croissant</h4>
                        <p style="color: #666; font-size: 0.9rem;">Rp 35.000</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <input type="number" value="2" min="1" style="width: 60px; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                    <strong style="color: var(--primary-color);">Rp 70.000</strong>
                    <button style="background: #ff4757; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div style="text-align: right;">
                <h3 style="margin-bottom: 15px; font-weight: 500;">Total: <span style="font-weight: 700; color: var(--primary-color); font-size: 1.5rem;">Rp 70.000</span></h3>
                <a href="checkout.php" class="btn-primary ripple-btn" style="text-decoration: none; display: inline-block; padding: 12px 30px; font-size: 1.1rem;">Lanjut ke Pembayaran</a>
            </div>
        </div>
    </div>
</section>
<?php include 'layouts/footer.php'; ?>
