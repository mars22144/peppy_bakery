<?php
require_once 'config/database.php';

// ── Handle POST actions (Add to Cart / Submit Review) ──────────────────────
if (session_status() === PHP_SESSION_NONE) session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $pdo = getDB();

    if ($action === 'add_to_cart') {
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

    if ($action === 'submit_review') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
            header('Location: login.php'); exit;
        }
        $pid      = (int)($_POST['product_id'] ?? 0);
        $rating   = min(5, max(1, (int)($_POST['rating'] ?? 5)));
        $komentar = trim($_POST['komentar'] ?? '');

        if ($pid > 0 && !empty($komentar)) {
            $ins_rev = $pdo->prepare('
                INSERT INTO reviews (id_user, id_produk, rating, komentar, tgl_review) 
                VALUES (?, ?, ?, ?, NOW())
            ');
            $ins_rev->execute([$_SESSION['user_id'], $pid, $rating, $komentar]);
            header("Location: product_detail.php?id=$pid&review_success=1"); exit;
        }
    }
}

include 'layouts/header.php';

$pdo        = getDB();
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Fetch from DB
$stmt = $pdo->prepare('SELECT * FROM products WHERE id_produk = ? LIMIT 1');
$stmt->execute([$product_id]);
$db_product = $stmt->fetch();

// Fetch reviews from DB
$rev_stmt = $pdo->prepare('
    SELECT r.*, u.nama AS user_name 
    FROM reviews r 
    JOIN users u ON r.id_user = u.id_user 
    WHERE r.id_produk = ? 
    ORDER BY r.tgl_review DESC
');
$rev_stmt->execute([$product_id]);
$db_reviews = $rev_stmt->fetchAll();

// Calculate review metrics
$review_count = count($db_reviews);
$total_rating = 0;
$rating_counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$mapped_reviews = [];

foreach ($db_reviews as $rev) {
    $total_rating += $rev['rating'];
    if (isset($rating_counts[$rev['rating']])) {
        $rating_counts[$rev['rating']]++;
    }
    
    // Check if this specific reviewer purchased the product
    $rev_purchased_stmt = $pdo->prepare('
        SELECT COUNT(*) FROM order_details od
        JOIN orders o ON od.id_order = o.id_order
        WHERE o.id_user = ? AND od.id_produk = ?
    ');
    $rev_purchased_stmt->execute([$rev['id_user'], $product_id]);
    $is_verified = $rev_purchased_stmt->fetchColumn() > 0;

    $mapped_reviews[] = [
        'name'        => $rev['user_name'],
        'date'        => date('d M Y, H:i', strtotime($rev['tgl_review'])),
        'rating'      => (int)$rev['rating'],
        'text'        => $rev['komentar'],
        'is_verified' => $is_verified
    ];
}

$average_rating = $review_count > 0 ? round($total_rating / $review_count, 1) : 0;

$rating_bars = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
if ($review_count > 0) {
    foreach ($rating_counts as $star => $count) {
        $rating_bars[$star] = round(($count / $review_count) * 100);
    }
}

// Merge DB data with static details (DB is source of truth for name/price/stock/image)
$s = $static[$product_id] ?? [
    'label'=>'PRODUK','unit'=>'/ buah','rating'=>4.5,'review_count'=>0,
    'weight'=>'-','shelf_life'=>'-','thumbs'=>[],'long_desc'=>'','ingredients'=>'-',
    'storage'=>[],'reviews'=>[],'rating_bars'=>[],
];

if ($db_product) {
    $p = array_merge($s, [
        'id'           => $db_product['id_produk'],
        'name'         => $db_product['nama_produk'],
        'price'        => $db_product['harga'],
        'price_fmt'    => 'Rp ' . number_format($db_product['harga'], 0, ',', '.'),
        'stock'        => $db_product['stok'],
        'image'        => $db_product['foto'] ?: ($s['thumbs'][0] ?? ''),
        'short_desc'   => $db_product['deskripsi'],
        'long_desc'    => $db_product['deskripsi'],
        'weight'       => $db_product['berat'] ?: '-',
        'shelf_life'   => $db_product['ketahanan'] ?: '-',
        'rating'       => $review_count > 0 ? $average_rating : 0,
        'review_count' => $review_count,
        'reviews'      => $mapped_reviews,
        'rating_bars'  => $rating_bars,
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
            <span class="detail-meta-label">Stok</span>
            <span class="detail-meta-value <?= $stock_class ?>"><?= $stock_text ?></span>
          </div>
          <div class="detail-meta-row">
            <span class="detail-meta-label">Berat</span>
            <span class="detail-meta-value"><?= $p['weight'] ?></span>
          </div>
          <div class="detail-meta-row">
            <span class="detail-meta-label">Ketahanan</span>
            <span class="detail-meta-value"><?= $p['shelf_life'] ?></span>
          </div>
          <div class="detail-meta-row">
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
                  Tambah ke Keranjang
                </button>
                <button type="submit" name="action" value="buy_now"
                  class="btn-dark ripple-btn btn-dark-centered">
                  Beli Sekarang
                </button>
              </form>
            </div>
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
        <button class="tab-btn" onclick="showTab('ulasan', this)">
          Ulasan (<?= $p['review_count'] ?>)
        </button>
      </div>

      <!-- Tab: Deskripsi -->
      <div id="tab-deskripsi" class="tab-pane active">
        <div class="detail-description">
          <div class="detail-ingredients">
            <p><?php foreach (explode("\n\n", $p['long_desc']) as $para): ?>
            <p><?= htmlspecialchars(trim($para)) ?></p>
          <?php endforeach; ?></p>
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
        <?php if (isset($_GET['review_success'])): ?>
          <div style="background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:0.9rem;">
            ✓ Ulasan Anda berhasil dikirim! Terima kasih atas masukan Anda.
          </div>
        <?php endif; ?>

        <!-- Rating Summary -->
        <div class="reviews-summary">
          <div class="reviews-score">
            <div class="reviews-score-num"><?= $p['rating'] ?></div>
            <div class="reviews-score-stars" style="color: #f5a623;">
              <?php
              $full  = floor($p['rating']);
              $half  = ($p['rating'] - $full) >= 0.5 ? 1 : 0;
              $empty = 5 - $full - $half;
              echo str_repeat('★', $full) . ($half ? '½' : '') . str_repeat('☆', $empty);
              ?>
            </div>
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

        <!-- Write Review Form -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
          <div class="write-review-box" style="background:#fffaf4; border:1px solid rgba(232,119,34,0.15); border-radius:12px; padding:20px; margin-bottom:30px;">
            <h4 style="margin-bottom:12px; color:var(--dark); font-family:'Ubuntu', sans-serif;">Tulis Ulasan Anda</h4>
            <form action="product_detail.php?id=<?= $product_id ?>" method="POST">
              <input type="hidden" name="action" value="submit_review">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">
              
              <div style="margin-bottom:12px;">
                <label style="display:block; margin-bottom:6px; font-size:0.9rem; font-weight:600; color:#555;">Rating</label>
                <div class="star-rating-select" style="display:flex; gap:6px; font-size:1.5rem; color:#f5a623; cursor:pointer;">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star-option" data-value="<?= $i ?>" onclick="setRating(<?= $i ?>)" style="transition: transform 0.2s;">☆</span>
                  <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="reviewRatingInput" value="5">
              </div>

              <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:6px; font-size:0.9rem; font-weight:600; color:#555;">Komentar</label>
                <textarea name="komentar" required placeholder="Tulis ulasan jujur Anda di sini..." style="width:100%; border:1px solid #ddd; border-radius:8px; padding:12px; font-size:0.92rem; outline:none; font-family:inherit; min-height:80px; resize:vertical;"></textarea>
              </div>

              <button type="submit" class="btn-primary ripple-btn" style="padding:10px 20px; font-size:0.9rem; border:none; border-radius:8px; cursor:pointer;">Kirim Ulasan</button>
            </form>
          </div>
        <?php else: ?>
          <div style="background:#f9f9f9; border-radius:12px; padding:16px; text-align:center; color:#777; margin-bottom:30px; font-size:0.9rem;">
            Silakan <a href="login.php" style="color:var(--orange); font-weight:700;">login sebagai customer</a> untuk menulis ulasan produk ini.
          </div>
        <?php endif; ?>

        <!-- Review Cards -->
        <div class="review-list">
          <?php foreach ($p['reviews'] as $r): ?>
            <div class="review-item">
              <div class="review-header">
                <div class="review-author">
                  <div class="review-avatar"><?= strtoupper($r['name'][0]) ?></div>
                  <div>
                    <div class="review-name">
                      <?= htmlspecialchars($r['name']) ?>
                      <?php if ($r['is_verified']): ?>
                        <span class="verified-badge" style="background:#e2f5e2; color:#2e7d32; font-size:0.7rem; font-weight:700; padding:2px 6px; border-radius:4px; margin-left:6px; display:inline-block; border:1px solid #bce2bc;">
                          ✓ Terverifikasi
                        </span>
                      <?php endif; ?>
                    </div>
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
          <?php if (empty($p['reviews'])): ?>
            <p style="text-align:center; color:#aaa; padding:20px 0;">Belum ada ulasan untuk produk ini.</p>
          <?php endif; ?>
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

  // === RATING STAR SELECTOR ===
  function setRating(val) {
    const input = document.getElementById('reviewRatingInput');
    if (input) input.value = val;
    const stars = document.querySelectorAll('.star-option');
    stars.forEach((star, index) => {
      if (index < val) {
        star.innerHTML = '★';
      } else {
        star.innerHTML = '☆';
      }
    });
  }
  // Initialize default rating stars on load
  document.addEventListener('DOMContentLoaded', () => {
    setRating(5);
  });
</script>

<?php include 'layouts/footer.php'; ?>