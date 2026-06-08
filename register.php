<?php include 'layouts/header.php'; ?>

<section class="page-section active auth-section">
    <div class="auth-place">
        <div class="auth-card">
            <h2 class="auth-title">Daftar Akun</h2>
            <form action="login.php" method="POST">
                <div class="auth-form-group">
                    <label class="auth-label">Nama Lengkap</label>
                    <input type="text" name="name" required class="auth-input" placeholder="Nama lengkap kamu">
                </div>
                <div class="auth-form-group">
                    <label class="auth-label">Email</label>
                    <input type="email" name="email" required class="auth-input" placeholder="contoh@email.com">
                </div>
                <div class="auth-form-group">
                    <label class="auth-label">Password</label>
                    <input type="password" name="password" required class="auth-input" placeholder="Min. 8 karakter">
                </div>
                <button type="submit" class="btn-primary ripple-btn btn-full">Daftar</button>
            </form>
            <p class="auth-footer">Sudah punya akun? <a href="login.php" class="auth-link">Login</a></p>
        </div>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>
