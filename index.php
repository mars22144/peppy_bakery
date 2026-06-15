<?php
require_once 'config/database.php';
$pdo = getDB();

// Fetch top 2 selling products
$stmt = $pdo->prepare("
    SELECT p.*, SUM(od.qty) as total_terjual 
    FROM products p 
    JOIN order_details od ON p.id_produk = od.id_produk 
    JOIN orders o ON od.id_order = o.id_order
    WHERE o.status_order != 'cancelled'
    GROUP BY p.id_produk 
    ORDER BY total_terjual DESC 
    LIMIT 2
");
$stmt->execute();
$featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If we have less than 2 products from sales, fallback to latest products
if (count($featured_products) < 2) {
    $exclude_ids = array_column($featured_products, 'id_produk');
    $placeholders = count($exclude_ids) > 0 ? implode(',', array_fill(0, count($exclude_ids), '?')) : '';
    $query = "SELECT * FROM products";
    if ($placeholders) {
        $query .= " WHERE id_produk NOT IN ($placeholders)";
    }
    $query .= " ORDER BY id_produk DESC LIMIT " . (2 - count($featured_products));
    $stmt_fallback = $pdo->prepare($query);
    $stmt_fallback->execute($exclude_ids);
    $fallback_products = $stmt_fallback->fetchAll(PDO::FETCH_ASSOC);
    $featured_products = array_merge($featured_products, $fallback_products);
}

include 'layouts/header.php';
?>

<!-- ==================== HOME SECTION ==================== -->
<section id="home" class="page-section active">

  <!-- HERO -->
  <div class="hero">
    <div class="hero-content reveal">
      <h1 class="hero-title">Selamat datang di<br><span class="hero-highlight">Peppy Bakery's</span></h1>
      <p class="hero-sub">Kami menghadirkan kehangatan roti artisan yang dibuat dengan tangan, menggunakan bahan - bahan pilihan untuk momen spesial Anda setiap hari</p>
      <a href="products.php" class="btn-primary ripple-btn">Beli Sekarang!</a>
    </div>
    <div class="hero-image reveal delay-2">
      <div class="hero-img-wrap">
        <img src="assets/img/croissant.png" alt="Artisan Bread" />
        <div class="hero-badge">
          <div>
            <strong>100% Organik</strong>
            <small>Tanpa pengawet buatan</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- FEATURED PRODUCTS -->
  <div class="featured-section">
    <h2 class="section-title reveal">Produk Unggulan Kami</h2>
    <div class="featured-grid">
      <?php if (isset($featured_products[0])): 
        $p1 = $featured_products[0];
        $img1 = !empty($p1['foto']) ? htmlspecialchars($p1['foto']) : 'assets/img/croissant.png';
      ?>
      <div class="featured-card dark reveal delay-1" onclick="window.location.href='product_detail.php?id=<?= $p1['id_produk'] ?>'" style="cursor:pointer;">
        <img src="<?= $img1 ?>" alt="<?= htmlspecialchars($p1['nama_produk']) ?>" />
        <div class="feat-overlay">
          <h3><?= htmlspecialchars($p1['nama_produk']) ?></h3>
          <p><?= htmlspecialchars($p1['deskripsi']) ?></p>
        </div>
      </div>
      <?php endif; ?>

      <?php if (isset($featured_products[1])): 
        $p2 = $featured_products[1];
        $img2 = !empty($p2['foto']) ? htmlspecialchars($p2['foto']) : 'assets/img/cak.jpg';
        $harga_k = number_format($p2['harga'] / 1000, 0) . 'k';
      ?>
      <div class="featured-card pink reveal delay-2" onclick="window.location.href='product_detail.php?id=<?= $p2['id_produk'] ?>'" style="cursor:pointer;">
        <div class="feat-text">
          <div class="feat-top">
            <span class="feat-name"><?= htmlspecialchars($p2['nama_produk']) ?></span>
            <span class="feat-price">Rp <?= $harga_k ?></span>
          </div>
          <p><?= htmlspecialchars($p2['deskripsi']) ?></p>
          <img src="<?= $img2 ?>" alt="<?= htmlspecialchars($p2['nama_produk']) ?>" />
        </div>
      </div>
      <?php endif; ?>

      <div class="featured-card light reveal delay-1" onclick="window.location.href='products.php'">
        <div class="feat-text">
          <span class="feat-icon fa-solid fa-car-side"></span>
          <h3>Pengiriman Seluruh Kota</h3>
          <p>Nikmati kesegaran roti kami langsung di depan pintu rumah Anda.</p>
          <a href="products.php" class="feat-link">Pesan Sekarang →</a>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include 'layouts/footer.php'; ?>