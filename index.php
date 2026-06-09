<?php include 'layouts/header.php'; ?>

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
        <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=500&q=80" alt="Artisan Bread" />
        <div class="hero-badge">
          <span class="badge-icon">🌿</span>
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
      <div class="featured-card dark reveal delay-1" onclick="window.location.href='products.php'" style="cursor:pointer;">
        <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=700&q=80" alt="Croissant" />
        <div class="feat-overlay">
          <span class="feat-tag">TERLARIS</span>
          <h3>Classic French Croissants</h3>
          <p>Lapisan mentega yang renyah di luar dan lembut di dalam.</p>
        </div>
      </div>
      <div class="featured-card pink reveal delay-2" onclick="window.location.href='products.php'">
        <div class="feat-text">
          <div class="feat-top">
            <span class="feat-name">Sourdough Loaf</span>
            <span class="feat-price">Rp 45k</span>
          </div>
          <p>Fermentasi alami 24 jam untuk rasa yang autentik.</p>
          <img src="https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=300&q=80" alt="Sourdough" />
        </div>
      </div>
      <div class="featured-card light reveal delay-1" onclick="window.location.href='products.php'">
        <div class="feat-text">
          <span class="feat-icon">🚚</span>
          <h3>Pengiriman Seluruh Kota</h3>
          <p>Nikmati kesegaran roti kami langsung di depan pintu rumah Anda.</p>
          <a href="products.php" class="feat-link">Pesan Sekarang →</a>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include 'layouts/footer.php'; ?>