<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = '/peppy_bakery';

// Calculate total cart quantity for badge
$cart_badge_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_badge_count = array_sum($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Peppy Bakery</title>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="<?= $base_url ?>/assets/img/logo-baru-transp.svg" rel="shortcut icon" sizes="16x16 32x32" type="image/svg+xml">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a class="nav-logo" href="<?= $base_url ?>/index.php">Peppy Bakery</a>
            <ul class="nav-links">
                <li><a href="<?= $base_url ?>/index.php" class="nav-link">Home</a></li>
                <li><a href="<?= $base_url ?>/products.php" class="nav-link">Product</a></li>
                <li><a href="<?= $base_url ?>/about.php" class="nav-link">About</a></li>
                <li><a href="<?= $base_url ?>/faq.php" class="nav-link">FAQ</a></li>
            </ul>

            <div class="nav-action-group">
                <?php if (isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role'] === 'customer'): ?>
                        <a href="<?= $base_url ?>/cart.php" class="nav-icon-link cart-icon-wrapper" id="cartIconDesktop">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if ($cart_badge_count > 0): ?>
                                <span class="cart-badge" id="cartBadgeDesktop"><?= $cart_badge_count ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="<?= $base_url ?>/customer/orders.php" class="nav-icon-link"><i class="fas fa-user"></i></a>
                    <?php elseif ($_SESSION['role'] === 'admin'): ?>
                        <a href="<?= $base_url ?>/admin/index_admin.php" class="nav-icon-link" title="Admin Dashboard"><i class="fas fa-chart-line"></i></a>
                    <?php endif; ?>
                    <a href="<?= $base_url ?>/logout.php" class="nav-logout-pill">Logout</a>
                <?php else: ?>
                    <a href="<?= $base_url ?>/login.php" class="nav-contact ripple-btn">Login</a>
                <?php endif; ?>
            </div>

            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>

        <div class="mobile-menu" id="mobileMenu">
            <a href="<?= $base_url ?>/index.php">Home</a>
            <a href="<?= $base_url ?>/products.php">Product</a>
            <a href="<?= $base_url ?>/about.php">About</a>
            <a href="<?= $base_url ?>/faq.php">FAQ</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
                <a href="<?= $base_url ?>/cart.php" class="cart-icon-wrapper" id="cartIconMobile">
                    <i class="fas fa-shopping-cart"></i> Cart
                    <?php if ($cart_badge_count > 0): ?>
                        <span class="cart-badge cart-badge-mobile" id="cartBadgeMobile"><?= $cart_badge_count ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= $base_url ?>/customer/orders.php"><i class="fas fa-user"></i> Pesanan</a>
                <a href="<?= $base_url ?>/logout.php" class="mobile-contact">Logout</a>
            <?php else: ?>
                <a href="<?= $base_url ?>/login.php" class="mobile-contact">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div style="padding-top: 64px;">
