<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['role'] = $_POST['role'];
    if ($_POST['role'] == 'admin') {
        header("Location: admin/index_admin.php");
    } else {
        header("Location: index.php");
    }
    exit;
}
include 'layouts/header.php';
?>

<section class="page-section active auth-section">
    <div class="auth-place">
        <div class="auth-card">
            <h2 class="auth-title">Login</h2>
            <form method="POST">
                <div class="auth-form-group">
                    <label class="auth-label">Email</label>
                    <input type="email" name="email" required class="auth-input" placeholder="contoh@email.com">
                </div>
                <div class="auth-form-group">
                    <label class="auth-label">Password</label>
                    <input type="password" name="password" required class="auth-input" placeholder="••••••••">
                </div>
                <div class="auth-form-group">
                    <label class="auth-label">Login Sebagai (Demo)</label>
                    <select name="role" class="auth-input">
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary ripple-btn btn-full">Masuk</button>
            </form>
            <p class="auth-footer">Belum punya akun? <a href="register.php" class="auth-link">Daftar di sini</a></p>
        </div>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>
