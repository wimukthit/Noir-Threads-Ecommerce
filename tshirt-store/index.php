<?php
$page_title = "Home";
$page_css = ['home.css'];
$page_js = ['home.js'];

// Session eka start karanna
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Featured products - real photos with Unsplash
$featured_products = [
    ['id' => 1, 'name' => 'Classic Crew Tee', 'price' => 2990.00, 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=500&fit=crop'],
    ['id' => 2, 'name' => 'Oversized Street Tee', 'price' => 3490.00, 'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=400&h=500&fit=crop'],
    ['id' => 3, 'name' => 'Ribbed Fitted Tee', 'price' => 2590.00, 'image' => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=400&h=500&fit=crop'],
    ['id' => 4, 'name' => 'Vintage Wash Tee', 'price' => 3290.00, 'image' => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=400&h=500&fit=crop'],
    ['id' => 5, 'name' => 'Pocket Tee', 'price' => 2790.00, 'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=400&h=500&fit=crop'],
    ['id' => 6, 'name' => 'Striped Long Sleeve', 'price' => 3890.00, 'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=400&h=500&fit=crop'],
    ['id' => 7, 'name' => 'Graphic Print Tee', 'price' => 3190.00, 'image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=400&h=500&fit=crop'],
    ['id' => 8, 'name' => 'Essential Tank Top', 'price' => 2190.00, 'image' => 'https://images.unsplash.com/photo-1554568218-0f1715e72254?w=400&h=500&fit=crop&crop=center'],
];

// Hero slider images
$hero_slides = [
    [
        'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=1600&h=900&fit=crop',
        'eyebrow' => 'New Drop',
        'title' => 'Wear it<br>your way',
        'desc' => 'Heavyweight cotton tees, cut for everyday life. Free delivery on orders over Rs. 5000.',
        'cta' => 'Shop the collection',
        'cta_link' => 'products.php'
    ],
    [
        'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=1600&h=900&fit=crop',
        'eyebrow' => 'Streetwear',
        'title' => 'Oversized.<br>On purpose.',
        'desc' => 'Dropped shoulders, boxy cut, built for layering.',
        'cta' => 'Shop Unisex',
        'cta_link' => 'products.php?category=3'
    ],
    [
        'image' => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=1600&h=900&fit=crop',
        'eyebrow' => 'Bestseller',
        'title' => 'Soft on skin.<br>Built to last.',
        'desc' => 'Pre-washed, pre-shrunk, ready for daily wear.',
        'cta' => 'Shop Women',
        'cta_link' => 'products.php?category=2'
    ],
];

// Categories
$categories = [
    ['id' => 1, 'name' => 'Men', 'image' => 'https://images.unsplash.com/photo-1612713160153-7a4f0f6d0a10?w=500&h=350&fit=crop'],
    ['id' => 2, 'name' => 'Women', 'image' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=500&h=350&fit=crop'],
    ['id' => 3, 'name' => 'Unisex', 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=350&fit=crop'],
    ['id' => 4, 'name' => 'Kids', 'image' => 'https://images.unsplash.com/photo-1622290291468-a28f7a7dc6a8?w=500&h=350&fit=crop'],
];

require_once 'includes/header.php';
?>

<!-- =========================================================
     HERO SLIDER
     ========================================================= -->
<section class="hero-slider">
  <?php foreach ($hero_slides as $index => $slide): ?>
  <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
    <img src="<?php echo $slide['image']; ?>" alt="<?php echo strip_tags($slide['title']); ?>">
    <div class="slide-content">
      <span class="slide-eyebrow"><?php echo $slide['eyebrow']; ?></span>
      <h1><?php echo $slide['title']; ?></h1>
      <p><?php echo $slide['desc']; ?></p>
      <a href="<?php echo $slide['cta_link']; ?>" class="btn btn-accent"><?php echo $slide['cta']; ?></a>
    </div>
  </div>
  <?php endforeach; ?>

  <button class="slider-arrow prev" aria-label="Previous slide">&#8249;</button>
  <button class="slider-arrow next" aria-label="Next slide">&#8250;</button>
  <div class="slider-dots"></div>
</section>

<!-- =========================================================
     CATEGORY STRIP
     ========================================================= -->
<section class="section container">
  <div class="section-head">
    <div>
      <span class="section-eyebrow">Browse</span>
      <h2>Shop by category</h2>
    </div>
  </div>
  <div class="category-strip">
    <?php foreach ($categories as $cat): ?>
      <a href="products.php?category=<?php echo $cat['id']; ?>" class="category-card">
        <img src="<?php echo $cat['image']; ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
        <span><?php echo htmlspecialchars($cat['name']); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- =========================================================
     FEATURED PRODUCTS
     ========================================================= -->
<section class="section container">
  <div class="section-head">
    <div>
      <span class="section-eyebrow">Handpicked</span>
      <h2>Featured tees</h2>
    </div>
    <a href="products.php" class="view-all">View all &rarr;</a>
  </div>
  <div class="product-grid">
    <?php foreach ($featured_products as $p): ?>
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
    <?php endforeach; ?>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>