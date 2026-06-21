<?php
require_once 'config/database.php';
include 'layouts/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location='login.php';</script>";
    exit;
}

$pdo = getDB();

// Clear direct buy session on regular page loads (GET requests)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    unset($_SESSION['direct_buy']);
}

// ── Handle cart actions ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Add to cart
    if ($action === 'add_to_cart') {
        $pid = (int)($_POST['product_id'] ?? 0);
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        if ($pid > 0) {
            $stmt = $pdo->prepare("SELECT stok FROM products WHERE id_produk = ?");
            $stmt->execute([$pid]);
            $prod = $stmt->fetch();
            if ($prod) {
                $stock = (int)$prod['stok'];
                $current_qty = isset($_SESSION['cart'][$pid]) ? $_SESSION['cart'][$pid] : 0;
                if ($current_qty + $qty > $stock) {
                    $_SESSION['error'] = 'tidak bisa melebihi stok yang ada';
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }
                    $_SESSION['cart'][$pid] = $stock;
                } else {
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }
                    if (isset($_SESSION['cart'][$pid])) {
                        $_SESSION['cart'][$pid] += $qty;
                    } else {
                        $_SESSION['cart'][$pid] = $qty;
                    }
                }
            }
        }
    }
    // Buy now (Direct purchase)
    if ($action === 'buy_now') {
        $pid = (int)($_POST['product_id'] ?? 0);
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        if ($pid > 0) {
            $stmt = $pdo->prepare("SELECT stok FROM products WHERE id_produk = ?");
            $stmt->execute([$pid]);
            $prod = $stmt->fetch();
            if ($prod) {
                $stock = (int)$prod['stok'];
                if ($qty > $stock) {
                    $_SESSION['error'] = 'tidak bisa melebihi stok yang ada';
                } else {
                    $_SESSION['direct_buy'] = [
                        $pid => $qty
                    ];
                    header('Location: checkout.php'); exit;
                }
            }
        }
    }
    // Update quantity
    if ($action === 'update_qty') {
        $pid = (int)($_POST['product_id'] ?? 0);
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        if ($pid > 0) {
            $stmt = $pdo->prepare("SELECT stok FROM products WHERE id_produk = ?");
            $stmt->execute([$pid]);
            $prod = $stmt->fetch();
            if ($prod) {
                $stock = (int)$prod['stok'];
                if ($qty > $stock) {
                    $_SESSION['error'] = 'tidak bisa melebihi stok yang ada';
                    if (isset($_SESSION['cart'][$pid])) {
                        $_SESSION['cart'][$pid] = $stock;
                    }
                } else {
                    if (isset($_SESSION['cart'][$pid])) {
                        $_SESSION['cart'][$pid] = $qty;
                    }
                }
            }
        }
    }
    // Remove item
    if ($action === 'remove') {
        $pid = (int)($_POST['product_id'] ?? 0);
        unset($_SESSION['cart'][$pid]);
    }
    // Clear cart
    if ($action === 'clear') {
        $_SESSION['cart'] = [];
    }
    header('Location: cart.php'); exit;
}

// ── Build cart from session ───────────────────────────────────────────────
$cart      = $_SESSION['cart'] ?? [];
$cart_rows = [];
$grand_total = 0;

if (!empty($cart)) {
    $ids   = array_keys($cart);
    $in    = implode(',', array_fill(0, count($ids), '?'));
    $stmt  = $pdo->prepare("SELECT * FROM products WHERE id_produk IN ($in)");
    $stmt->execute($ids);
    $prods = $stmt->fetchAll(PDO::FETCH_UNIQUE); // keyed by id_produk

    foreach ($cart as $pid => $qty) {
        if (isset($prods[$pid])) {
            $p = $prods[$pid];
            // Automatic correction if cart qty exceeds DB stock
            if ($qty > (int)$p['stok']) {
                $qty = (int)$p['stok'];
                $_SESSION['cart'][$pid] = $qty;
            }
            $subtotal      = $p['harga'] * $qty;
            $grand_total  += $subtotal;
            $cart_rows[]   = ['product' => $p, 'qty' => $qty, 'subtotal' => $subtotal];
        }
    }
}
?>

<section class="page-section active cart-section">
    <div class="page-medium">
        <h2 class="cart-page-title">Keranjang Belanja</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="auth-alert auth-alert--error" style="margin-bottom: 20px;">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($cart_rows)): ?>
            <div class="cart-card" style="text-align:center;padding:60px 20px;">
                <p style="font-size:3rem;margin-bottom:12px;" class="fa-solid fa-cart-shopping"></p>
                <p style="color:#888;margin-bottom:20px;">Keranjang kamu masih kosong.</p>
                <a href="products.php" class="btn-primary ripple-btn">Lihat Produk</a>
            </div>
        <?php else: ?>

        <div class="cart-card">
            <?php foreach ($cart_rows as $row): $p = $row['product']; ?>
            <div class="cart-item">
                <div class="cart-item-left">
                    <img src="<?= htmlspecialchars($p['foto']) ?>"
                        class="cart-item-img" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                    <div>
                        <h4 class="cart-item-name"><?= htmlspecialchars($p['nama_produk']) ?></h4>
                        <p class="cart-item-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
                    </div>
                </div>
                <div class="cart-item-right">
                    <form method="POST" style="display:flex;align-items:center;gap:8px;">
                        <input type="hidden" name="action" value="update_qty">
                        <input type="hidden" name="product_id" value="<?= $pid ?>">
                        <input type="number" name="qty" value="<?= $row['qty'] ?>"
                            min="1" max="<?= $p['stok'] ?>" class="cart-qty-input"
                            onchange="this.form.submit()">
                    </form>
                    <strong class="cart-item-subtotal">Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></strong>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="product_id" value="<?= $pid ?>">
                        <button type="submit" class="cart-delete-btn"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 class="cart-total-label">Total:
                    <span class="cart-total-amount">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
                </h3>
                <a href="checkout.php" class="btn-primary ripple-btn btn-lg" style="margin-top: 15px;">
                    Lanjut ke Pembayaran
                </a>
            </div>
        </div>

        <?php endif; ?>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>