<?php
require_once 'config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect if already logged in
if (isset($_SESSION['role'])) {
    header('Location: ' . ($_SESSION['role'] === 'admin' ? 'admin/index_admin.php' : 'index.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } else {
        $pdo  = getDB();
        $stmt = $pdo->prepare('SELECT id_user, nama, email, password, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['name']    = $user['nama'];
            $_SESSION['role']    = $user['role'];

            header('Location: ' . ($user['role'] === 'admin' ? 'admin/index_admin.php' : 'index.php'));
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    }
}

include 'layouts/header.php';
?>

<section class="page-section active auth-section">
    <div class="auth-place">
        <div class="auth-card">
            <h2 class="auth-title">Login</h2>

            <?php if ($error): ?>
                <div class="auth-alert auth-alert--error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="auth-form-group">
                    <label class="auth-label">Email</label>
                    <input type="email" name="email" required class="auth-input"
                        placeholder="contoh@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="auth-form-group">
                    <label class="auth-label">Password</label>
                    <input type="password" name="password" required class="auth-input" placeholder="••••••••">
                </div>
                <button type="submit" class="btn-primary ripple-btn btn-full">Masuk</button>
            </form>
            <p class="auth-footer">Belum punya akun? <a href="register.php" class="auth-link">Daftar di sini</a></p>
        </div>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>
