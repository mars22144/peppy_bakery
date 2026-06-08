<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
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
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Peppy Bakery</h2>
        <p style="color: #aaa; font-size: 0.8rem; margin-top: 5px;">Admin Panel</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="<?= $base_url ?>/admin/index_admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="<?= $base_url ?>/admin/products_admin.php"><i class="fas fa-box"></i> Kelola Produk</a></li>
        <li><a href="<?= $base_url ?>/admin/orders_admin.php"><i class="fas fa-shopping-bag"></i> Pesanan</a></li>
        <li><a href="<?= $base_url ?>/admin/users_admin.php"><i class="fas fa-users"></i> Pelanggan</a></li>
        <li style="margin-top: 30px;"><a href="<?= $base_url ?>/logout.php" style="color: #ff4757;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content Wrapper -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div>
            <h3 style="font-weight: 500;">Halo, Admin!</h3>
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 35px; height: 35px; background: var(--admin-primary); color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>
    
    <!-- Page Content -->
    <div class="content-area">
