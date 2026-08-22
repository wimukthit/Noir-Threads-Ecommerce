<?php
require_once 'config/db.php';
$page_title = "Home";

$featured = $conn->query("SELECT * FROM products WHERE featured = 1 LIMIT 8");
$categories = $conn->query("SELECT * FROM categories");

$cat_images = [
    'Men' => 'https://placehold.co/500x350/1a1a1a/f7f5f2?text=Men',
    'Women' => 'https://placehold.co/500x350/2b2b2b/f7f5f2?text=Women',
    'Unisex' => 'https://placehold.co/500x350/6b6a66/f7f5f2?text=Unisex',
    'Kids' => 'https://placehold.co/500x350/ff4e32/1a1a1a?text=Kids',
];

require_once 'includes/header.php';
?>

<!-- ============ HERO SLIDER (sliding photos) ============ -->
<section class="hero-slider">
  <div class="slide active">
    <img src="https://placehold.co/1600x900/1a1a1a/f7f5f2?text=Noir+Threads" alt="Model wearing the Classic Crew Tee">
    <div class="slide-content">
      <span class="slide-eyebrow">New Drop</span>
      <h1>Wear it<br>your way</h1>
      <p>Heavyweight cotton tees, cut for everyday life. Free delivery on orders over Rs. 5000.</p>
      <a href="products.php" class="btn btn-accent">Shop the collection</a>
    </div>
  </div>
  <div class="slide">
    <img src="https://placehold.co/1600x900/2b2b2b/f7f5f2?text=Oversized+Fit" alt="Oversized street tee">
    <div class="slide-content">
      <span class="slide-eyebrow">Streetwear</span>
      <h1>Oversized.<br>On purpose.</h1>
      <p>Dropped shoulders, boxy cut, built for layering.</p>
      <a href="products.php?category=3" class="btn btn-accent">Shop Unisex</a>
    </div>
  </div>
  <div class="slide">
    <img src="https://placehold.co/1600x900/6b6a66/f7f5f2?text=Soft+Cotton" alt="Ribbed fitted tee">
    <div class="slide-content">
      <span class="slide-eyebrow">Bestseller</span>
      <h1>Soft on skin.<br>Built to last.</h1>
      <p>Pre-washed, pre-shrunk, ready for daily wear.</p>
      <a href="products.php?category=2" class="btn btn-accent">Shop Women</a>
    </div>
  </div>

  <button class="slider-arrow prev" aria-label="Previous slide">&#8249;</button>
  <button class="slider-arrow next" aria-label="Next slide">&#8250;</button>
  <div class="slider-dots"></div>
</section>

<!-- ============ CATEGORY STRIP ============ -->
<section class="section container">
  <div class="section-head">
    <div>
      <span class="section-eyebrow">Browse</span>
      <h2>Shop by category</h2>
    </div>
  </div>
  <div class="category-strip">
    <?php while ($cat = $categories->fetch_assoc()): ?>
      <a href="products.php?category=<?php echo $cat['id']; ?>" class="category-card">
        <img src="<?php echo $cat_images[$cat['name']] ?? 'https://placehold.co/500x350/1a1a1a/f7f5f2?text=' . urlencode($cat['name']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
        <span><?php echo htmlspecialchars($cat['name']); ?></span>
      </a>
    <?php endwhile; ?>
  </div>
</section>

<!-- ============ FEATURED PRODUCTS ============ -->
<section class="section container">
  <div class="section-head">
    <div>
      <span class="section-eyebrow">Handpicked</span>
      <h2>Featured tees</h2>
    </div>
    <a href="products.php" class="view-all">View all &rarr;</a>
  </div>
  <div class="product-grid">
    <?php while ($p = $featured->fetch_assoc()): ?>
      <a href="product.php?id=<?php echo $p['id']; ?>" class="product-card">
        <div class="product-thumb">
          <span class="product-tag">Featured</span>
          <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
        </div>
        <div class="product-info">
          <h3><?php echo htmlspecialchars($p['name']); ?></h3>
          <div class="product-price-row">
            <span class="product-price">Rs. <?php echo number_format($p['price'], 2); ?></span>
          </div>
        </div>
      </a>
    <?php endwhile; ?>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
