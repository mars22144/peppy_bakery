<?php
require_once 'config/database.php';
include 'layouts/header.php';

$pdo = getDB();
$products = $pdo->query('SELECT * FROM products ORDER BY id_produk DESC')->fetchAll();
?>

<!-- ==================== PRODUCT SECTION ==================== -->
<section id="product" class="page-section active" style="padding-top: 40px; padding-bottom: 80px;">

  <div class="product-hero">
    <div class="product-header reveal">
      <span class="prod-label">OUR DAILY BAKE</span>
      <h1 class="prod-title">DAFTAR<br><span>MENU</span></h1>
      <p>Setiap roti di Peppy Bakery dibuat dari fermentasi yang lambat. Dipanggang setiap hari di oven berbahan bakar kayu kami untuk menghasilkan kerak yang renyah dan remah yang ringan serta aromatik.</p>
    </div>

    <div class="product-grid">
      <?php foreach ($products as $i => $p): ?>
      <?php
        $img = htmlspecialchars($p['foto']);
        $pid = (int)$p['id_produk'];
        $card_class = ($i === 0 && $p['id_produk']) ? 'prod-card featured-prod' : 'prod-card';
        $delay_classes = ['reveal delay-1','reveal delay-2','reveal delay-1','reveal delay-2','reveal delay-3'];
        $d = $delay_classes[$i % 5];
      ?>
      <div class="<?= $card_class ?> <?= $d ?>" onclick="window.location.href='product_detail.php?id=<?= $pid ?>'">
        <div class="prod-img-wrap">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>"/>
          <?php if ($p['id_produk']): ?><span class="prod-badge">Bestseller</span><?php endif; ?>
        </div>
        <div class="prod-info">
          <div class="prod-name-row">
            <h3><?= htmlspecialchars($p['nama_produk']) ?></h3>
            <span class="prod-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></span>
          </div>
          <p><?= htmlspecialchars($p['deskripsi']) ?></p>
          <a href="product_detail.php?id=<?= $pid ?>" class="btn-primary ripple-btn"
            style="text-decoration:none;display:inline-block;margin-top:15px;">
            <?= ($i === 0 && $p['id_produk']) ? 'Detail &amp; Pesan' : 'Pesan' ?>
          </a>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if (empty($products)): ?>
      <p style="grid-column:1/-1;text-align:center;color:#aaa;padding:40px 0;">Belum ada produk tersedia.</p>
      <?php endif; ?>
    </div>

    <!-- Custom Orders -->
    <div class="custom-orders reveal" style="margin-top: 50px;">
      <div class="co-text">
        <h3>Custom Orders</h3>
        <p>Merencanakan acara spesial atau membutuhkan pesanan katering dalam jumlah besar? Kami membuat kue pesanan khusus, kami akan membalas chat anda dalam 48 jam.</p>
      </div>
      <button class="btn-dark ripple-btn" onclick="inquireNow()">Chat Sekarang</button>
    </div>
  </div>

</section>

<?php include 'layouts/footer.php'; ?>
