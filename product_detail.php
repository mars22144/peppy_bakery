<?php
require_once 'config/database.php';

// ── Handle Add to Cart (POST) ─────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
        header('Location: login.php'); exit;
    }
    $pid = (int)($_POST['product_id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    if ($pid > 0) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid] += $qty;
        } else {
            $_SESSION['cart'][$pid] = $qty;
        }
    }
    header('Location: cart.php'); exit;
}

include 'layouts/header.php';

$pdo        = getDB();
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Fetch from DB
$stmt = $pdo->prepare('SELECT * FROM products WHERE id_produk = ? LIMIT 1');
$stmt->execute([$product_id]);
$db_product = $stmt->fetch();

// Merge DB data with static details (DB is source of truth for name/price/stock/image)
$s = $static[$product_id] ?? [
    'label'=>'PRODUK','unit'=>'/ buah','rating'=>4.5,'review_count'=>0,
    'weight'=>'-','shelf_life'=>'-','thumbs'=>[],'long_desc'=>'','ingredients'=>'-',
    'storage'=>[],'reviews'=>[],'rating_bars'=>[],
];

if ($db_product) {
    $p = array_merge($s, [
        'id'        => $db_product['id_produk'],
        'name'      => $db_product['nama_produk'],
        'price'     => $db_product['harga'],
        'price_fmt' => 'Rp ' . number_format($db_product['harga'], 0, ',', '.'),
        'stock'     => $db_product['stok'],
        'image'     => $db_product['foto'] ?: ($s['thumbs'][0] ?? ''),
        'short_desc'=> $db_product['deskripsi'],
    ]);
} else {
    // Redirect if product not found
    header('Location: products.php'); exit;
}

// Stock display
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

// Related products from DB
$related_stmt = $pdo->prepare('SELECT * FROM products WHERE id_produk != ? ORDER BY RAND() LIMIT 4');
$related_stmt->execute([$product_id]);
$related_db = $related_stmt->fetchAll();
// Map to shape expected by the template
$related = array_map(fn($r) => [
    'id'         => $r['id_produk'],
    'name'       => $r['nama_produk'],
    'short_desc' => $r['deskripsi'],
    'price_fmt'  => 'Rp ' . number_format($r['harga'], 0, ',', '.'),
    'image'      => $r['foto'],
], $related_db);
?>

<!-- ==================== PRODUCT DETAIL ==================== -->
<section class="page-section active detail-section">
  <div class="detail-container">

    <!-- Breadcrumb -->
    <nav class="breadcrumb reveal">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">></span>
      <a href="products.php">Produk</a>
      <span class="breadcrumb-sep">></span>
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
              <button class="detail-qty-btn" onclick="changeQty(-1)" type="button">-</button>
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