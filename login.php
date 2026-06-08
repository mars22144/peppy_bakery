<?php
session_start();
// Dummy login for testing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['role'] = $_POST['role']; // 'admin' or 'customer'
    if ($_POST['role'] == 'admin') {
        header("Location: admin/index_admin.php");
    } else {
        header("Location: index.php");
    }
    exit;
}
include 'layouts/header.php'; 
?>
<section class="page-section active" style="padding-top: 100px; padding-bottom: 100px; display: flex; justify-content: center;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 400px;">
        <h2 style="font-family: 'Playfair Display', serif; text-align: center; margin-bottom: 30px; color: var(--text-dark);">Login</h2>
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'DM Sans', sans-serif;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'DM Sans', sans-serif;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Login Sebagai (Demo)</label>
                <select name="role" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'DM Sans', sans-serif;">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn-primary ripple-btn" style="width: 100%; border: none; font-size: 1rem; cursor: pointer;">Masuk</button>
        </form>
        <p style="text-align: center; margin-top: 20px; color: #666;">Belum punya akun? <a href="register.php" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Daftar di sini</a></p>
    </div>
</section>

