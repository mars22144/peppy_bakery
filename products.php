<?php
require_once 'config/database.php';
include 'layouts/header.php';

$pdo = getDB();
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE nama_produk LIKE :search ORDER BY id_produk DESC');
    $stmt->execute(['search' => '%' . $search . '%']);
    $products = $stmt->fetchAll();
} else {
    $products = $pdo->query('SELECT * FROM products ORDER BY id_produk DESC')->fetchAll();
}
?>

<!-- ==================== PRODUCT SECTION ==================== -->
<section id="product" class="page-section active" style="padding-top: 40px; padding-bottom: 80px;">

  <div class="product-hero">
    <div class="product-header reveal">
      <span class="prod-label">OUR DAILY BAKE</span>
      <h1 class="prod-title">DAFTAR<br><span>MENU</span></h1>
      <p>Setiap roti di Peppy Bakery dibuat dari fermentasi yang lambat. Dipanggang setiap hari di oven berbahan bakar kayu kami untuk menghasilkan kerak yang renyah dan remah yang ringan serta aromatik.</p>
    </div>

    <div class="search-container reveal">
      <form action="products.php" method="GET" class="search-form">
        <input type="text" name="search" class="search-input" placeholder="Cari roti..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn-primary ripple-btn search-btn">Cari</button>
      </form>
    </div>

    <div class="product-grid">
      <?php foreach ($products as $i => $p): ?>
      <?php
        $img = htmlspecialchars($p['foto']);
        $pid = (int)$p['id_produk'];
        $is_oos = ((int)$p['stok'] <= 0);
        $card_class = ($i === 0 && $p['id_produk']) ? 'prod-card featured-prod' : 'prod-card';
        if ($is_oos) $card_class .= ' out-of-stock';
        $delay_classes = ['reveal delay-1','reveal delay-2','reveal delay-1','reveal delay-2','reveal delay-3'];
        $d = $delay_classes[$i % 5];
      ?>
      <div class="<?= $card_class ?> <?= $d ?>" <?= !$is_oos ? "onclick=\"window.location.href='product_detail.php?id={$pid}'\"" : '' ?>>
        <div class="prod-img-wrap">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>"/>
          <?php if ($is_oos): ?>
            <div class="prod-oos-overlay">
              <span class="prod-oos-label">Stok Habis</span>
            </div>
          <?php elseif ($p['id_produk']): ?>
          <?php endif; ?>
        </div>
        <div class="prod-info">
          <div class="prod-name-row">
            <h3><?= htmlspecialchars($p['nama_produk']) ?></h3>
            <span class="prod-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></span>
          </div>
          <p><?= htmlspecialchars($p['deskripsi']) ?></p>
          <?php if ($is_oos): ?>
            <span class="btn-out-of-stock">Stok Habis</span>
            <button class="btn-cart-quick btn-disabled" disabled title="Stok Habis">
              <i class="fa-solid fa-cart-plus"></i>
            </button>
          <?php else: ?>
            <a href="product_detail.php?id=<?= $pid ?>" class="btn-primary ripple-btn"
              style="text-decoration:none;display:inline-block;margin-top:15px;vertical-align:middle;">
              <?= ($i === 0 && $p['id_produk']) ? 'Pesan' : 'Pesan' ?>
            </a>
            <button class="btn-cart-quick quick-add-cart ripple-btn" data-id="<?= $pid ?>" title="Tambah langsung ke keranjang">
              <i class="fa-solid fa-cart-plus"></i>
            </button>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if (empty($products)): ?>
      <p style="grid-column:1/-1;text-align:center;color:#aaa;padding:40px 0;">
        <?= $search !== '' ? 'Roti dengan nama "' . htmlspecialchars($search) . '" tidak ditemukan.' : 'Belum ada produk tersedia.' ?>
      </p>
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

<script>
document.querySelectorAll('.quick-add-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const productId = this.getAttribute('data-id');
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('qty', 1);

        fetch('add_to_cart_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                
                // Update Desktop Badge
                const badgeDesktop = document.getElementById('cartBadgeDesktop');
                if (badgeDesktop) {
                    badgeDesktop.textContent = data.total_qty;
                    badgeDesktop.style.display = 'inline-block';
                }
                
                // Update Mobile Badge
                const badgeMobile = document.getElementById('cartBadgeMobile');
                if (badgeMobile) {
                    badgeMobile.textContent = data.total_qty;
                    badgeMobile.style.display = 'inline-block';
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menambahkan produk ke keranjang.');
        });
    });
});
</script>

<?php include 'layouts/footer.php'; ?>

