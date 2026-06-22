<?php
require_once 'config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Check role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Silakan login sebagai customer terlebih dahulu.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
    ]);
    exit;
}

$pid = (int)($_POST['product_id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));

if ($pid <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID Produk tidak valid.'
    ]);
    exit;
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id_produk = ? LIMIT 1');
    $stmt->execute([$pid]);
    $product = $stmt->fetch();

    if (!$product) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Produk tidak ditemukan.'
        ]);
        exit;
    }

    $stock = (int)$product['stok'];
    $cart_qty = isset($_SESSION['cart'][$pid]) ? (int)$_SESSION['cart'][$pid] : 0;

    if ($cart_qty + $qty > $stock) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Stok produk tidak mencukupi. Sisa stok: ' . ($stock - $cart_qty)
        ]);
        exit;
    }

    // Add to session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$pid] = $cart_qty + $qty;

    $total_qty = array_sum($_SESSION['cart']);

    echo json_encode([
        'status' => 'success',
        'message' => htmlspecialchars($product['nama_produk']) . ' berhasil ditambahkan ke keranjang.',
        'total_qty' => $total_qty
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan pada database: ' . $e->getMessage()
    ]);
    exit;
}
