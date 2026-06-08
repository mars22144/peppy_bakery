<?php
include 'layouts/header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location='login.php';</script>";
    exit;
}
?>

<section class="page-section active cart-section">
    <div class="page-medium">
        <h2 class="cart-page-title">Keranjang Belanja</h2>

        <div class="cart-card">
            <!-- Cart Item -->
            <div class="cart-item">
                <div class="cart-item-left">
                    <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=100&q=80"
                        class="cart-item-img" alt="Croissant">
                    <div>
                        <h4 class="cart-item-name">Classic French Croissant</h4>
                        <p class="cart-item-price">Rp 35.000</p>
                    </div>
                </div>
                <div class="cart-item-right">
                    <input type="number" value="2" min="1" class="cart-qty-input">
                    <strong class="cart-item-subtotal">Rp 70.000</strong>
                    <button class="cart-delete-btn"><i class="fas fa-trash"></i></button>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 class="cart-total-label">Total: <span class="cart-total-amount">Rp 70.000</span></h3>
                <a href="checkout.php" class="btn-primary ripple-btn btn-lg" style="margin-top: 15px;">
                    Lanjut ke Pembayaran
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>