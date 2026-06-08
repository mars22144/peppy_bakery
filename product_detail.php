<?php
include 'layouts/header.php';

// Simulasi data produk berdasarkan ID
// Nanti diganti dengan query ke database
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

$products = [
  1 => [
    'id'          => 1,
    'name'        => 'Classic French Croissant',
    'label'       => 'BESTSELLER',
    'price'       => 35000,
    'price_fmt'   => 'Rp 35.000',
    'unit'        => '/ buah',
    'rating'      => 4.9,
    'review_count' => 128,
    'stock'       => 24,
    'weight'      => '85 gram',
    'shelf_life'  => '2–3 hari',
    'image'       => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=800&q=80',
    'thumbs'      => [
      'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=200&q=80',
      'https://images.unsplash.com/photo-1549903072-7e6e0bedb7fb?w=200&q=80',
      'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=200&q=80',
    ],
    'short_desc'  => 'Multi-layered honeycomb structure with a shattering crust and an intense aroma of fermented French butter. Three days in the making.',
    'long_desc'   => 'Croissant kami dibuat melalui proses laminasi tiga hari dengan 27 lapisan mentega Normandy pilihan. Setiap gigitan menghasilkan suara renyah khas yang diikuti tekstur lembut dan beraroma butter intens.

Adonan diistirahatkan semalam di suhu dingin untuk mengembangkan kompleksitas rasa, kemudian dipanggang dalam oven batu bersuhu tinggi hingga berwarna coklat keemasan sempurna.',
    'ingredients' => 'Tepung terigu protein tinggi, mentega Normandy 84% fat, susu segar, gula, garam laut, ragi alami.',
    'storage'     => [
      ['icon' => '🌡️', 'title' => 'Suhu Ruang',  'desc' => 'Simpan dalam wadah kedap udara, tahan 2–3 hari.'],
      ['icon' => '❄️', 'title' => 'Kulkas',       'desc' => 'Bungkus rapat, tahan hingga 5 hari. Hangatkan 3 menit di oven 160°C sebelum disajikan.'],
      ['icon' => '🧊', 'title' => 'Freezer',      'desc' => 'Dapat disimpan hingga 1 bulan. Thaw semalam di kulkas, lalu panaskan di oven.'],
      ['icon' => '☀️', 'title' => 'Tips Sajian',  'desc' => 'Paling nikmat disajikan hangat dalam 30 menit setelah dipanggang ulang.'],
    ],
    'reviews'     => [
      ['name' => 'Siti R.',    'rating' => 5, 'date' => '3 hari lalu',   'text' => 'Croissant terenak yang pernah saya coba! Renyah di luar, lembut di dalam. Aroma butternya luar biasa. Pasti order lagi!'],
      ['name' => 'Budi S.',    'rating' => 5, 'date' => '1 minggu lalu', 'text' => 'Kualitas bakery premium, harga terjangkau. Pengiriman juga cepat dan roti masih fresh waktu sampai.'],
      ['name' => 'Dewi L.',    'rating' => 4, 'date' => '2 minggu lalu', 'text' => 'Enak banget! Cuma sayang sedikit terlalu manis buat selera saya. Tapi tetap worth it sih untuk kualitas segini.'],
    ],
    'rating_bars' => [5 => 85, 4 => 10, 3 => 3, 2 => 1, 1 => 1],
  ],
  2 => [
    'id'          => 2,
    'name'        => 'Rustic Sourdough Loaf',
    'label'       => 'SIGNATURE',
    'price'       => 65000,
    'price_fmt'   => 'Rp 65.000',
    'unit'        => '/ loaf',
    'rating'      => 4.8,
    'review_count' => 96,
    'stock'       => 5,
    'weight'      => '400 gram',
    'shelf_life'  => '4–5 hari',
    'image'       => 'https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=800&q=80',
    'thumbs'      => [
      'https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=200&q=80',
      'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=200&q=80',
    ],
    'short_desc'  => 'Our signature 48-hour fermented loaf with a dark, blistered crust and a tangy, custard-like crumb.',
    'long_desc'   => 'Sourdough loaf kami difermentasi selama 48 jam menggunakan starter ragi alami yang sudah berusia 3 tahun. Proses fermentasi lambat ini menghasilkan cita rasa yang kompleks — asam ringan, manis alami, dan aroma biji-bijian yang kaya.

Dipanggang dalam dutch oven batu untuk menghasilkan kulit yang gelap, melepuh, dan retakan alami yang khas.',
    'ingredients' => 'Tepung gandum stone-ground, air, garam laut, ragi alami (starter sourdough).',
    'storage'     => [
      ['icon' => '🌡️', 'title' => 'Suhu Ruang',  'desc' => 'Simpan terbungkus kain linen bersih, tahan 4–5 hari.'],
      ['icon' => '❄️', 'title' => 'Kulkas',       'desc' => 'Tidak disarankan — kulkas membuat sourdough cepat basi.'],
      ['icon' => '🧊', 'title' => 'Freezer',      'desc' => 'Iris dulu sebelum dibekukan. Panggang langsung dari frozen di 200°C selama 5–8 menit.'],
      ['icon' => '🔪', 'title' => 'Tips Potong',  'desc' => 'Tunggu minimal 1 jam setelah dipanggang sebelum diiris agar crumb tidak kempes.'],
    ],
    'reviews'     => [
      ['name' => 'Ahmad K.', 'rating' => 5, 'date' => '5 hari lalu',  'text' => 'Sourdough autentik! Asam-manisnya pas, kulit luarnya beneran renyah. Mirip yang di bakery artisan mahal tapi harganya jauh lebih masuk akal.'],
      ['name' => 'Nina P.',  'rating' => 5, 'date' => '1 minggu lalu', 'text' => 'Sudah langganan setiap minggu. Rotinya konsisten enak dan fresh setiap kali order.'],
    ],
    'rating_bars' => [5 => 88, 4 => 8, 3 => 3, 2 => 1, 1 => 0],
  ],
  3 => [
    'id'          => 3,
    'name'        => 'Pain au Chocolat',
    'label'       => 'FAVORIT',
    'price'       => 42000,
    'price_fmt'   => 'Rp 42.000',
    'unit'        => '/ buah',
    'rating'      => 4.7,
    'review_count' => 74,
    'stock'       => 18,
    'weight'      => '90 gram',
    'shelf_life'  => '2–3 hari',
    'image'       => 'https://images.unsplash.com/photo-1549903072-7e6e0bedb7fb?w=800&q=80',
    'thumbs'      => [
      'https://images.unsplash.com/photo-1549903072-7e6e0bedb7fb?w=200&q=80',
    ],
    'short_desc'  => 'Buttery laminated dough wrapped around two batons of 70% dark Valrhona chocolate.',
    'long_desc'   => 'Pain au Chocolat kami menggunakan dua batang coklat Valrhona 70% yang dibalut adonan laminasi butter Normandy. Diproses tiga hari seperti croissant kami, menghasilkan lapisan yang sempurna dengan sensasi lumer coklat premium di setiap gigitan.',
    'ingredients' => 'Tepung terigu, mentega Normandy 84%, coklat Valrhona 70%, susu, gula, garam, ragi alami.',
    'storage'     => [
      ['icon' => '🌡️', 'title' => 'Suhu Ruang', 'desc' => 'Simpan dalam wadah tertutup, tahan 2–3 hari.'],
      ['icon' => '❄️', 'title' => 'Kulkas',      'desc' => 'Bungkus rapat, tahan 5 hari. Hangatkan 3 menit di oven 160°C.'],
    ],
    'reviews'     => [
      ['name' => 'Rini W.', 'rating' => 5, 'date' => '2 hari lalu', 'text' => 'Coklat Valrhona-nya kerasa banget! Adonannya lembut dan berlapis sempurna. Ini yang terenak dari semua yang pernah saya coba.'],
    ],
    'rating_bars' => [5 => 80, 4 => 14, 3 => 4, 2 => 1, 1 => 1],
  ],
];

// Fallback ke produk pertama jika ID tidak ditemukan
if (!isset($products[$product_id])) {
  $product_id = 1;
}
$p = $products[$product_id];

// Status stok
if ($p['stock'] === 0) {
  $stock_class = 'detail-stock-out';
  $stock_text  = 'Habis';
} elseif ($p['stock'] <= 5) {
  $stock_class = 'detail-stock-low';
  $stock_text  = 'Stok Menipis (' . $p['stock'] . ' tersisa)';
} else {
  $stock_class = 'detail-stock-ok';
  $stock_text  = 'Tersedia (' . $p['stock'] . ' unit)';
}

// Related products (produk lain selain yang sedang ditampilkan)
$related = array_filter($products, fn($item) => $item['id'] !== $product_id);
$related = array_slice($related, 0, 4);
?>

<!-- ==================== PRODUCT DETAIL ==================== -->
<section class="page-section active detail-section">
  <div class="detail-container">

    <!-- Breadcrumb -->
    <nav class="breadcrumb reveal">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">›</span>
      <a href="products.php">Produk</a>
      <span class="breadcrumb-sep">›</span>
      <span class="breadcrumb-current"><?= htmlspecialchars($p['name']) ?></span>
    </nav>

    <!-- Main Grid -->
    <div class="detail-grid">

      <!-- ===== IMAGE SIDE ===== -->
      <div class="reveal">
        <div class="detail-img-wrap">
          <img src="<?= $p['image'] ?>" alt="<?= htmlspecialchars($p['name']) ?>"
            class="detail-img-main" id="mainImage">
          <?php if (!empty($p['label'])): ?>
            <span class="detail-img-badge"><?= $p['label'] ?></span>
          <?php endif; ?>
        </div>
        <?php if (!empty($p['thumbs'])): ?>
          <div class="detail-thumbnails">
            <?php foreach ($p['thumbs'] as $i => $thumb): ?>
              <div class="detail-thumb <?= $i === 0 ? 'active' : '' ?>"
                onclick="switchImage(this, '<?= str_replace('w=200', 'w=800', $thumb) ?>')">
                <img src="<?= $thumb ?>" alt="Foto <?= $i + 1 ?>">
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- ===== INFO SIDE ===== -->
      <div class="detail-info reveal delay-1">
        <span class="detail-label"><?= $p['label'] ?></span>
        <h1 class="detail-title"><?= htmlspecialchars($p['name']) ?></h1>

        <!-- Rating -->
        <div class="detail-rating">
          <span class="detail-stars">
            <?php
            $full  = floor($p['rating']);
            $half  = ($p['rating'] - $full) >= 0.5 ? 1 : 0;
            $empty = 5 - $full - $half;
            echo str_repeat('★', $full) . ($half ? '½' : '') . str_repeat('☆', $empty);
            ?>
          </span>
          <span class="detail-rating-num"><?= $p['rating'] ?></span>
          <span class="detail-rating-count">(<?= $p['review_count'] ?> ulasan)</span>
        </div>

        <!-- Price -->
        <div class="detail-price">
          <span class="detail-price-main"><?= $p['price_fmt'] ?></span>
          <span class="detail-price-unit"><?= $p['unit'] ?></span>
        </div>

        <!-- Short Desc -->
        <p class="detail-desc"><?= htmlspecialchars($p['short_desc']) ?></p>

        <!-- Meta -->
        <div class="detail-meta">
          <div class="detail-meta-row">
            <span class="detail-meta-icon">📦</span>
            <span class="detail-meta-label">Stok</span>
            <span class="detail-meta-value <?= $stock_class ?>"><?= $stock_text ?></span>
          </div>
          <div class="detail-meta-row">
            <span class="detail-meta-icon">⚖️</span>
            <span class="detail-meta-label">Berat</span>
            <span class="detail-meta-value"><?= $p['weight'] ?></span>
          </div>
          <div class="detail-meta-row">
            <span class="detail-meta-icon">🕐</span>
            <span class="detail-meta-label">Ketahanan</span>
            <span class="detail-meta-value"><?= $p['shelf_life'] ?></span>
          </div>
          <div class="detail-meta-row">
            <span class="detail-meta-icon">🚚</span>
            <span class="detail-meta-label">Pengiriman</span>
            <span class="detail-meta-value">Hari yang sama (order sebelum jam 10)</span>
          </div>
        </div>

        <!-- Order / CTA -->
        <div class="detail-order">
          <div class="detail-qty-row">
            <span class="detail-qty-label">Jumlah</span>
            <div class="detail-qty-control">
              <button class="detail-qty-btn" onclick="changeQty(-1)" type="button">−</button>
              <input type="number" id="qtyInput" class="detail-qty-input"
                value="1" min="1" max="<?= $p['stock'] ?>">
              <button class="detail-qty-btn" onclick="changeQty(1)" type="button">+</button>
            </div>
          </div>

          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
            <div class="detail-cta-row">
              <form action="cart.php" method="POST" class="form-display-contents">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <input type="hidden" name="qty" id="formQty" value="1">
                <button type="submit" name="action" value="add_to_cart"
                  class="btn-primary ripple-btn">
                  🛒 Tambah ke Keranjang
                </button>
              </form>
              <button class="btn-wishlist" id="wishlistBtn" onclick="toggleWishlist(this)" type="button"
                title="Simpan ke wishlist">♡</button>
            </div>
            <a href="checkout.php" class="btn-dark ripple-btn" class="btn-dark-centered">
              Beli Sekarang →
            </a>
          <?php else: ?>
            <div class="detail-login-notice">
              <span>🔒</span>
              <span>Silakan <a href="login.php">login</a> atau <a href="register.php">daftar</a> untuk memesan produk ini.</span>
            </div>
          <?php endif; ?>
        </div>

      </div><!-- /detail-info -->
    </div><!-- /detail-grid -->

    <!-- ===== TABS ===== -->
    <div class="detail-tabs reveal">
      <div class="tab-nav">
        <button class="tab-btn active" onclick="showTab('deskripsi', this)">Deskripsi</button>
        <button class="tab-btn" onclick="showTab('penyimpanan', this)">Cara Penyimpanan</button>
        <button class="tab-btn" onclick="showTab('ulasan', this)">
          Ulasan (<?= $p['review_count'] ?>)
        </button>
      </div>

      <!-- Tab: Deskripsi -->
      <div id="tab-deskripsi" class="tab-pane active">
        <div class="detail-description">
          <?php foreach (explode("\n\n", $p['long_desc']) as $para): ?>
            <p><?= htmlspecialchars(trim($para)) ?></p>
          <?php endforeach; ?>
          <div class="detail-ingredients">
            <h4>🌾 Bahan-bahan</h4>
            <p><?= htmlspecialchars($p['ingredients']) ?></p>
          </div>
        </div>
      </div>

      <!-- Tab: Penyimpanan -->
      <div id="tab-penyimpanan" class="tab-pane">
        <div class="storage-list">
          <?php foreach ($p['storage'] as $s): ?>
            <div class="storage-item">
              <div class="storage-item-icon"><?= $s['icon'] ?></div>
              <div class="storage-item-body">
                <h5><?= htmlspecialchars($s['title']) ?></h5>
                <p><?= htmlspecialchars($s['desc']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Tab: Ulasan -->
      <div id="tab-ulasan" class="tab-pane">

        <!-- Rating Summary -->
        <div class="reviews-summary">
          <div class="reviews-score">
            <div class="reviews-score-num"><?= $p['rating'] ?></div>
            <div class="reviews-score-stars">★★★★★</div>
            <div class="reviews-score-count"><?= $p['review_count'] ?> ulasan</div>
          </div>
          <div class="reviews-bars">
            <?php foreach ([5, 4, 3, 2, 1] as $star): ?>
              <div class="review-bar-row">
                <span class="review-bar-label"><?= $star ?> ★</span>
                <div class="review-bar-track">
                  <div class="review-bar-fill"
                    style="width: <?= $p['rating_bars'][$star] ?? 0 ?>%"></div>
                </div>
                <span class="review-bar-count"><?= $p['rating_bars'][$star] ?? 0 ?>%</span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Review Cards -->
        <div class="review-list">
          <?php foreach ($p['reviews'] as $r): ?>
            <div class="review-item">
              <div class="review-header">
                <div class="review-author">
                  <div class="review-avatar"><?= strtoupper($r['name'][0]) ?></div>
                  <div>
                    <div class="review-name"><?= htmlspecialchars($r['name']) ?></div>
                    <div class="review-date"><?= $r['date'] ?></div>
                  </div>
                </div>
                <div class="review-stars">
                  <?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?>
                </div>
              </div>
              <p class="review-text"><?= htmlspecialchars($r['text']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>

      </div><!-- /tab-ulasan -->
    </div><!-- /detail-tabs -->

    <!-- ===== RELATED PRODUCTS ===== -->
    <?php if (!empty($related)): ?>
      <div class="related-section reveal">
        <h3 class="related-title">Produk Lainnya</h3>
        <div class="related-grid">
          <?php foreach ($related as $rel): ?>
            <div class="prod-card" onclick="window.location.href='product_detail.php?id=<?= $rel['id'] ?>'" class="card-clickable">
              <div class="prod-img-wrap">
                <img src="<?= $rel['image'] ?>" alt="<?= htmlspecialchars($rel['name']) ?>" />
              </div>
              <div class="prod-info">
                <div class="prod-name-row">
                  <h3><?= htmlspecialchars($rel['name']) ?></h3>
                </div>
                <p><?= htmlspecialchars($rel['short_desc']) ?></p>
                <span class="prod-price orange"><?= $rel['price_fmt'] ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

  </div><!-- /detail-container -->
</section>

<script>
  // === THUMBNAIL SWITCH ===
  function switchImage(thumb, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.detail-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
  }

  // === QUANTITY CONTROL ===
  function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    const formQty = document.getElementById('formQty');
    let val = parseInt(input.value) + delta;
    const max = parseInt(input.max) || 99;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
    if (formQty) formQty.value = val;
  }
  document.getElementById('qtyInput')?.addEventListener('change', function() {
    const formQty = document.getElementById('formQty');
    if (formQty) formQty.value = this.value;
  });

  // === TABS ===
  function showTab(name, btn) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
  }

  // === WISHLIST TOGGLE ===
  function toggleWishlist(btn) {
    btn.classList.toggle('active');
    btn.innerHTML = btn.classList.contains('active') ? '♥' : '♡';
  }
</script>

<?php include 'layouts/footer.php'; ?>