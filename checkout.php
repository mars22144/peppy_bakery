<?php
include 'layouts/header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php"); exit;
}
?>

<section class="page-section active checkout-section">
    <div class="page-checkout">
        <h2 class="page-title-main">Checkout</h2>

        <div class="checkout-card">
            <form action="customer/orders.php" method="POST">

                <h3 class="checkout-section-title">Data Pengiriman</h3>
                <div class="checkout-form-group">
                    <label class="auth-label">Alamat Lengkap</label>
                    <textarea required class="auth-input textarea-address"></textarea>
                </div>
                <div class="checkout-form-group">
                    <label class="auth-label">Nomor HP</label>
                    <input type="text" required class="auth-input" placeholder="08xx-xxxx-xxxx">
                </div>

                <h3 class="checkout-section-title checkout-payment-title">Metode Pembayaran</h3>
                <div class="checkout-form-group">
                    <select required class="auth-input">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="transfer">Transfer Bank (BCA / Mandiri)</option>
                        <option value="ewallet">E-Wallet (Gopay / OVO)</option>
                        <option value="cod">Cash on Delivery (COD)</option>
                    </select>
                </div>

                <div class="checkout-summary">
                    <p class="checkout-total-label">Total Bayar: <span class="checkout-total-amount">Rp 70.000</span></p>
                    <button type="submit" class="btn-primary ripple-btn" class="btn-checkout-submit">
                        Buat Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>
