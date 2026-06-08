<?php include 'layouts/header.php'; ?>

<!-- ==================== PRODUCT SECTION ==================== -->
<section id="product" class="page-section active" style="padding-top: 40px; padding-bottom: 80px;">

  <div class="product-hero">
    <div class="product-header reveal">
      <span class="prod-label">OUR DAILY BAKE</span>
      <h1 class="prod-title">DAFTAR<br><span>MENU</span></h1>
      <p>Each loaf at Peppy Bakery is a testament to the slow art of fermentation. We use only stone ground heritage grains and natural leavens, baked daily in our wood fired oven to achieve that signature shattering crust and airy, aromatic crumb.</p>
    </div>

    <div class="product-grid">
      <!-- Featured large item -->
      <div class="prod-card featured-prod reveal delay-1">
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=700&q=80" alt="Croissant"/>
          <span class="prod-badge">Bestseller</span>
        </div>
        <div class="prod-info">
          <div class="prod-name-row">
            <h3>Classic French Croissant</h3>
            <span class="prod-price">Rp 35.000</span>
          </div>
          <p>Multi-layered honeycomb structure with a shattering crust and an intense aroma of fermented French butter. Three days in the making.</p>
          <a href="product_detail.php?id=1" class="btn-primary ripple-btn" style="text-decoration:none; display:inline-block; margin-top: 15px;">Detail & Pesan</a>
        </div>
      </div>

      <!-- Sourdough -->
      <div class="prod-card reveal delay-2">
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=500&q=80" alt="Sourdough"/>
        </div>
        <div class="prod-info">
          <h3>Rustic Sourdough Loaf</h3>
          <p>Our signature 48 hour fermented loaf with a dark, blistered crust and a tangy, custard like crumb.</p>
          <span class="prod-price orange" style="font-weight: 700; color: #d97706;">Rp 65.000</span>
          <br>
          <a href="product_detail.php?id=2" class="btn-primary ripple-btn" style="text-decoration:none; display:inline-block; margin-top: 15px; padding: 8px 16px; font-size: 0.9rem;">Pesan</a>
        </div>
      </div>

      <!-- Pain au Chocolat -->
      <div class="prod-card reveal delay-1">
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1549903072-7e6e0bedb7fb?w=500&q=80" alt="Pain au Chocolat"/>
        </div>
        <div class="prod-info">
          <h3>Pain au Chocolat</h3>
          <p>Buttery laminated dough wrapped around two batons of 70% dark Valrhona chocolate.</p>
          <span class="prod-price orange" style="font-weight: 700; color: #d97706;">Rp 42.000</span>
          <br>
          <a href="product_detail.php?id=3" class="btn-primary ripple-btn" style="text-decoration:none; display:inline-block; margin-top: 15px; padding: 8px 16px; font-size: 0.9rem;">Pesan</a>
        </div>
      </div>

      <!-- Cranberry Walnut -->
      <div class="prod-card reveal delay-2">
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500&q=80" alt="Cranberry Walnut Rye"/>
        </div>
        <div class="prod-info">
          <h3>Cranberry Walnut Rye</h3>
          <p>A dense, earthy rye loaf packed with tart cranberries and toasted walnuts for a complex finish.</p>
          <span class="prod-price orange" style="font-weight: 700; color: #d97706;">Rp 78.000</span>
          <br>
          <a href="product_detail.php?id=4" class="btn-primary ripple-btn" style="text-decoration:none; display:inline-block; margin-top: 15px; padding: 8px 16px; font-size: 0.9rem;">Pesan</a>
        </div>
      </div>

      <!-- Brioche -->
      <div class="prod-card reveal delay-3">
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500&q=80" alt="Brioche Burger Buns"/>
        </div>
        <div class="prod-info">
          <h3>Brioche Burger Buns</h3>
          <p>Pillowy and rich with eggs and butter, topped with toasted sesame. The ultimate burger canvas.</p>
          <span class="prod-price orange" style="font-weight: 700; color: #d97706;">Rp 55.000</span>
          <br>
          <a href="product_detail.php?id=5" class="btn-primary ripple-btn" style="text-decoration:none; display:inline-block; margin-top: 15px; padding: 8px 16px; font-size: 0.9rem;">Pesan</a>
        </div>
      </div>
    </div>

    <!-- Custom Orders -->
    <div class="custom-orders reveal" style="margin-top: 50px;">
      <div class="co-text">
        <h3>Custom Orders</h3>
        <p>Planning a special event or need a large catering batch? We hand bake custom orders with 48 hours' notice.</p>
      </div>
      <button class="btn-dark ripple-btn">Inquire Now →</button>
    </div>

  </div>

</section>

<?php include 'layouts/footer.php'; ?>
