<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}
$base_url = '/peppy_bakery';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Peppy Bakery</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/admin.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Peppy Bakery</h2>
        <p class="sidebar-panel-label">Admin Panel</p>
    </div>
    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
    <ul class="sidebar-menu">
        <li><a href="<?= $base_url ?>/admin/index_admin.php" class="<?= $current_page == 'index_admin.php' ? 'active' : '' ?>"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="<?= $base_url ?>/admin/products_admin.php" class="<?= $current_page == 'products_admin.php' ? 'active' : '' ?>"><i class="fas fa-box"></i> Kelola Produk</a></li>
        <li><a href="<?= $base_url ?>/admin/orders_admin.php" class="<?= ($current_page == 'orders_admin.php' || $current_page == 'order_detail_admin.php') ? 'active' : '' ?>"><i class="fas fa-shopping-bag"></i> Pesanan</a></li>
        <li><a href="<?= $base_url ?>/admin/users_admin.php" class="<?= $current_page == 'users_admin.php' ? 'active' : '' ?>"><i class="fas fa-users"></i> Pelanggan</a></li>
        <li class="sidebar-logout-item"><a href="<?= $base_url ?>/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content Wrapper -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div>
            <h3 class="topbar-greeting">Halo, Admin!</h3>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content-area">
