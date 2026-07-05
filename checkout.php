<?php
require_once 'config/database.php';
include 'layouts/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php'); exit;
}

$pdo       = getDB();
$is_direct = isset($_SESSION['direct_buy']) && !empty($_SESSION['direct_buy']);
$cart      = $is_direct ? $_SESSION['direct_buy'] : ($_SESSION['cart'] ?? []);
$error     = '';

// Redirect if cart is empty
if (empty($cart)) {
    header('Location: cart.php'); exit;
}

// Fetch products in cart
$ids   = array_keys($cart);
$in    = implode(',', array_fill(0, count($ids), '?'));
$stmt  = $pdo->prepare("SELECT * FROM products WHERE id_produk IN ($in)");
$stmt->execute($ids);
$prods = $stmt->fetchAll(PDO::FETCH_UNIQUE);

// Validate stock on page load
$has_stock_error = false;
foreach ($cart as $pid => $qty) {
    if (isset($prods[$pid])) {
        if ($qty > (int)$prods[$pid]['stok']) {
            $has_stock_error = true;
            if (!$is_direct) {
                $_SESSION['cart'][$pid] = (int)$prods[$pid]['stok'];
            }
        }
    } else {
        if (!$is_direct) {
            unset($_SESSION['cart'][$pid]);
        }
    }
}
if ($has_stock_error) {
    $_SESSION['error'] = 'tidak bisa melebihi stok yang ada';
    header('Location: cart.php'); exit;
}

// ── Handle checkout submission ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $payment = trim($_POST['payment'] ?? '');
    $kurir   = trim($_POST['kurir']   ?? '');

    if (!$address || !$phone || !$payment || !$kurir) {
        $error = 'Semua field wajib diisi.';
    } else {
        // Validate stock again before committing order
        $stock_ok = true;
        foreach ($cart as $pid => $qty) {
            if (!isset($prods[$pid]) || $qty > (int)$prods[$pid]['stok']) {
                $stock_ok = false;
                break;
            }
        }

        if (!$stock_ok) {
            $error = 'Stok untuk produk di keranjang kamu tidak mencukupi atau telah berubah. Silakan kembali ke keranjang.';
        } else {
            $total = 0;
            foreach ($cart as $pid => $qty) {
                if (isset($prods[$pid])) $total += $prods[$pid]['harga'] * $qty;
            }

        try {
            $pdo->beginTransaction();

            // 1. Insert order
            $ins = $pdo->prepare(
                'INSERT INTO orders (id_user, ttl_harga, status_order) VALUES (?, ?, "pending")'
            );
            $ins->execute([$_SESSION['user_id'], $total]);
            $order_id = $pdo->lastInsertId();

            // 2. Insert order_details
            $ins_detail = $pdo->prepare(
                'INSERT INTO order_details (id_order, id_produk, nama_produk, harga, qty, sub_total) VALUES (?,?,?,?,?,?)'
            );
            foreach ($cart as $pid => $qty) {
                if (isset($prods[$pid])) {
                    $pr = $prods[$pid];
                    $sub = $pr['harga'] * $qty;
                    $ins_detail->execute([$order_id, $pid, $pr['nama_produk'], $pr['harga'], $qty, $sub]);
                    
                    // Decrement stock
                    $pdo->prepare('UPDATE products SET stok = GREATEST(0, stok - ?) WHERE id_produk = ?')
                        ->execute([$qty, $pid]);
                }
            }

            // 3. Insert payment (Initial status)
            $ins_pay = $pdo->prepare(
                'INSERT INTO payments (id_order, metode_nayar, status_bayar) VALUES (?, ?, "unpaid")'
            );
            $ins_pay->execute([$order_id, $payment]);

            // Generate auto no_resi
            $clean_kurir = preg_replace('/[^A-Za-z0-9]/', '', $kurir);
            $no_resi     = 'PBK-' . strtoupper($clean_kurir) . '-' . rand(100000000, 999999999);

            // 4. Insert shipping
            $ins_ship = $pdo->prepare(
                'INSERT INTO shipping (id_order, alamat_kirim, kurir, no_resi, status_kirim) VALUES (?, ?, ?, ?, "pending")'
            );
            $ins_ship->execute([$order_id, $address, $kurir, $no_resi]);

            $pdo->commit();

            // Clear cart
            if ($is_direct) {
                unset($_SESSION['direct_buy']);
            } else {
                $_SESSION['cart'] = [];
                $pdo->prepare('DELETE FROM carts WHERE id_user = ?')->execute([$_SESSION['user_id']]);
            }
            header('Location: customer/orders.php?success=1'); exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Gagal memproses pesanan: ' . $e->getMessage();
        }
        }
    }
}

// ── Rebuild cart total for display ───────────────────────────────────────
$ids   = array_keys($cart);
$in    = implode(',', array_fill(0, count($ids), '?'));
$stmt  = $pdo->prepare("SELECT * FROM products WHERE id_produk IN ($in)");
$stmt->execute($ids);
$prods = $stmt->fetchAll(PDO::FETCH_UNIQUE);

$grand_total = 0;
$items_display = [];
foreach ($cart as $pid => $qty) {
    if (isset($prods[$pid])) {
        $p = $prods[$pid];
        $subtotal = $p['harga'] * $qty;
        $grand_total += $subtotal;
        $items_display[] = ['name' => $p['nama_produk'], 'qty' => $qty, 'subtotal' => $subtotal];
    }
}
?>

<section class="page-section active checkout-section">
    <div class="page-checkout">
        <h2 class="page-title-main">Checkout</h2>

        <?php if ($error): ?>
            <div class="auth-alert auth-alert--error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="checkout-card">
            <!-- Order Summary -->
            <div style="margin-bottom:20px;">
                <h3 class="checkout-section-title">Ringkasan Pesanan</h3>
                <?php foreach ($items_display as $item): ?>
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f0e8d8;font-size:0.92rem;">
                    <span><?= htmlspecialchars($item['name']) ?> x <?= $item['qty'] ?></span>
                    <span>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <form action="checkout.php" method="POST">
                <h3 class="checkout-section-title">Data Pengiriman</h3>
                <div class="checkout-form-group">
                    <label class="auth-label">Alamat Lengkap</label>
                    <textarea name="address" required class="auth-input textarea-address"
                        placeholder="Masukkan alamat lengkap pengiriman"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                </div>
                <div class="checkout-form-group">
                    <label class="auth-label">Nomor HP</label>
                    <input type="text" name="phone" required class="auth-input"
                        placeholder="08xx-xxxx-xxxx"
                        value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>

                <h3 class="checkout-section-title">Jasa Pengiriman (Kurir)</h3>
                <div class="checkout-form-group">
                    <select name="kurir" required class="auth-input">
                        <option value="">Pilih Kurir</option>
                        <option value="JNE" <?= ($_POST['kurir'] ?? '') === 'JNE' ? 'selected':'' ?>>JNE</option>
                        <option value="J&T" <?= ($_POST['kurir'] ?? '') === 'J&T' ? 'selected':'' ?>>J&T</option>
                        <option value="SiCepat" <?= ($_POST['kurir'] ?? '') === 'SiCepat' ? 'selected':'' ?>>SiCepat</option>
                        <option value="GoSend" <?= ($_POST['kurir'] ?? '') === 'GoSend' ? 'selected':'' ?>>GoSend</option>
                        <option value="GrabExpress" <?= ($_POST['kurir'] ?? '') === 'GrabExpress' ? 'selected':'' ?>>GrabExpress</option>
                    </select>
                </div>

                <h3 class="checkout-section-title checkout-payment-title">Metode Pembayaran</h3>
                <div class="checkout-form-group">
                    <select name="payment" required class="auth-input">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="transfer" <?= ($_POST['payment'] ?? '') === 'transfer' ? 'selected':'' ?>>Transfer Bank (BCA / Mandiri)</option>
                        <option value="ewallet"  <?= ($_POST['payment'] ?? '') === 'ewallet'  ? 'selected':'' ?>>E-Wallet (Gopay / OVO)</option>
                        <option value="cod"      <?= ($_POST['payment'] ?? '') === 'cod'      ? 'selected':'' ?>>Cash on Delivery (COD)</option>
                    </select>
                </div>

                <div class="checkout-summary">
                    <p class="checkout-total-label">Total Bayar:
                        <span class="checkout-total-amount">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
                    </p>
                    <button type="submit" class="btn-primary ripple-btn">Buat Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>