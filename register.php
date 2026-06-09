<?php
require_once 'config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['role'])) {
    header('Location: index.php'); exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || !$password) {
        $error = 'Semua field wajib diisi.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        $pdo = getDB();
        $chk = $pdo->prepare('SELECT id_user FROM users WHERE email = ? LIMIT 1');
        $chk->execute([$email]);
        if ($chk->fetch()) {
            $error = 'Email sudah terdaftar. Silakan gunakan email lain.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins  = $pdo->prepare('INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, "customer")');
            $ins->execute([$name, $email, $hash]);
            $success = 'Akun berhasil dibuat! Silakan login.';
        }
    }
}

include 'layouts/header.php';
?>

<section class="page-section active auth-section">
    <div class="auth-place">
        <div class="auth-card">
            <h2 class="auth-title">Daftar Akun</h2>

            <?php if ($error): ?>
                <div class="auth-alert auth-alert--error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="auth-alert auth-alert--success"><?= htmlspecialchars($success) ?>
                    <a href="login.php" class="auth-link"> Login sekarang</a>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="auth-form-group">
                    <label class="auth-label">Nama Lengkap</label>
                    <input type="text" name="name" required class="auth-input"
                        placeholder="Nama lengkap kamu"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div class="auth-form-group">
                    <label class="auth-label">Email</label>
                    <input type="email" name="email" required class="auth-input"
                        placeholder="contoh@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="auth-form-group">
                    <label class="auth-label">Password</label>
                    <input type="password" name="password" required class="auth-input" placeholder="Min. 6 karakter">
                </div>
                <button type="submit" class="btn-primary ripple-btn btn-full">Daftar</button>
            </form>
            <p class="auth-footer">Sudah punya akun? <a href="login.php" class="auth-link">Login</a></p>
        </div>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>
