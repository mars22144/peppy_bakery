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
      <div class="featured-card dark reveal delay-1" onclick="window.location.href='products.php'" style="cursor:pointer;">
        <img src="assets/img/croissant.png" alt="Croissant" />
        <div class="feat-overlay">
          <h3>Classic French Croissants</h3>
          <p>Lapisan mentega yang renyah di luar dan lembut di dalam.</p>
        </div>
      </div>
      <div class="featured-card pink reveal delay-2" onclick="window.location.href='products.php'">
        <div class="feat-text">
          <div class="feat-top">
            <span class="feat-name">Butter Cake</span>
            <span class="feat-price">Rp 50k</span>
          </div>
          <p>Kue classic yang mengandalkan mentega sebagai bahan utama.
          </p>
          <img src="assets/img/cak.jpg" alt="Butter Cake" />
        </div>
      </div>
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