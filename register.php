<?php include 'layouts/header.php'; ?>
<section class="page-section active" style="padding-top: 100px; padding-bottom: 100px; display: flex; justify-content: center;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 400px;">
        <h2 style="font-family: 'Playfair Display', serif; text-align: center; margin-bottom: 30px; color: var(--text-dark);">Daftar Akun</h2>
        <form action="login.php" method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Lengkap</label>
                <input type="text" name="name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'DM Sans', sans-serif;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'DM Sans', sans-serif;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'DM Sans', sans-serif;">
            </div>
            <button type="submit" class="btn-primary ripple-btn" style="width: 100%; border: none; font-size: 1rem; cursor: pointer;">Daftar</button>
        </form>
        <p style="text-align: center; margin-top: 20px; color: #666;">Sudah punya akun? <a href="login.php" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Login</a></p>
    </div>
</section>
<?php include 'layouts/footer.php'; ?>
