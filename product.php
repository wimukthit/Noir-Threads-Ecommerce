<?php
require_once 'config/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit;
}

$page_title = $product['name'];
$sizes = explode(',', $product['sizes']);
$colors = explode(',', $product['colors']);

// Rating summary
$rating_row = $conn->query("SELECT COUNT(*) AS total, COALESCE(AVG(rating),0) AS avg_rating FROM reviews WHERE product_id = $id")->fetch_assoc();
$review_count = $rating_row['total'];
$avg_rating = round($rating_row['avg_rating'], 1);

// Reviews list
$rstmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
$rstmt->bind_param("i", $id);
$rstmt->execute();
$reviews = $rstmt->get_result();

// Gallery: main image first, then any extra photos
$gallery_images = [$product['image']];
$gstmt = $conn->prepare("SELECT image_url FROM product_images WHERE product_id = ? ORDER BY sort_order ASC");
$gstmt->bind_param("i", $id);
$gstmt->execute();
$gres = $gstmt->get_result();
while ($g = $gres->fetch_assoc()) {
    $gallery_images[] = $g['image_url'];
}

function star_string($rating) {
    $full = round($rating);
    $out = '<span class="stars">';
    for ($i = 1; $i <= 5; $i++) {
        $out .= '<span class="' . ($i <= $full ? 'filled' : '') . '">&#9733;</span>';
    }
    $out .= '</span>';
    return $out;
}

require_once 'includes/header.php';
?>

<section class="section container">
  <div class="product-detail">
    <div class="pd-gallery">
      <div class="zoom-wrap" id="mainImageWrap">
        <img src="<?php echo htmlspecialchars($gallery_images[0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" id="mainImage">
        <?php if (count($gallery_images) > 1): ?>
          <button type="button" class="slider-arrow prev pd-arrow" id="pdPrev" aria-label="Previous photo">&#8249;</button>
          <button type="button" class="slider-arrow next pd-arrow" id="pdNext" aria-label="Next photo">&#8250;</button>
        <?php endif; ?>
      </div>
      <?php if (count($gallery_images) > 1): ?>
        <div class="pd-thumbs">
          <?php foreach ($gallery_images as $i => $img): ?>
            <img src="<?php echo htmlspecialchars($img); ?>" class="pd-thumb <?php echo $i == 0 ? 'active' : ''; ?>" data-src="<?php echo htmlspecialchars($img); ?>" alt="View <?php echo $i + 1; ?>">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="pd-info">
      <span class="section-eyebrow"><?php echo htmlspecialchars($product['category_name']); ?></span>
      <h1><?php echo htmlspecialchars($product['name']); ?></h1>

      <div class="rating-line">
        <?php echo star_string($avg_rating); ?>
        <span><?php echo $avg_rating; ?> (<?php echo $review_count; ?> review<?php echo $review_count == 1 ? '' : 's'; ?>)</span>
      </div>

      <div class="pd-price">Rs. <?php echo number_format($product['price'], 2); ?></div>

      <?php if ($product['stock'] <= 0): ?>
        <span class="stock-badge out">Out of Stock</span><br><br>
      <?php elseif ($product['stock'] <= 10): ?>
        <span class="stock-badge low">Only <?php echo $product['stock']; ?> left</span><br><br>
      <?php endif; ?>

      <p class="pd-desc"><?php echo htmlspecialchars($product['description']); ?></p>

      <form action="add_to_cart.php" method="post" id="addToCartForm">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

        <div class="option-group">
          <label class="title">Size</label>
          <div class="swatches">
            <?php foreach ($sizes as $i => $s): ?>
              <label class="swatch">
                <input type="radio" name="size" value="<?php echo trim($s); ?>" <?php echo $i == 0 ? 'checked' : ''; ?>>
                <?php echo trim($s); ?>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="option-group">
          <label class="title">Color</label>
          <div class="swatches">
            <?php foreach ($colors as $i => $c): ?>
              <label class="swatch">
                <input type="radio" name="color" value="<?php echo trim($c); ?>" <?php echo $i == 0 ? 'checked' : ''; ?>>
                <?php echo trim($c); ?>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="qty-row">
          <label class="title" style="margin:0;">Quantity</label>
          <div class="qty-control">
            <button type="button" onclick="changeQty(-1)">&minus;</button>
            <input type="number" id="qtyInput" name="quantity" value="1" min="1" max="<?php echo max(1, $product['stock']); ?>" readonly>
            <button type="button" onclick="changeQty(1)">&plus;</button>
          </div>
          <span class="stock-note"><?php echo $product['stock']; ?> in stock</span>
        </div>

        <button type="submit" class="btn btn-accent btn-block" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
          <?php echo $product['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
        </button>
      </form>
    </div>
  </div>

  <!-- ============ REVIEWS ============ -->
  <div class="reviews-section" id="reviews">
    <div class="section-head">
      <div>
        <span class="section-eyebrow">Customer Feedback</span>
        <h2 style="font-size:28px;">Reviews (<?php echo $review_count; ?>)</h2>
      </div>
    </div>

    <?php if ($reviews->num_rows === 0): ?>
      <p style="color:var(--muted);">No reviews yet. Be the first to review this product.</p>
    <?php else: ?>
      <?php while ($r = $reviews->fetch_assoc()): ?>
        <div class="review-card">
          <div class="review-head">
            <span class="review-name"><?php echo htmlspecialchars($r['user_name']); ?></span>
            <span class="review-date"><?php echo date("d M Y", strtotime($r['created_at'])); ?></span>
          </div>
          <?php echo star_string($r['rating']); ?>
          <p class="review-comment"><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p>
          <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $r['user_id']): ?>
            <a href="delete_review.php?id=<?php echo $r['id']; ?>" class="review-delete" onclick="return confirm('Delete your review?');">Delete my review</a>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
      <div class="review-form">
        <h4 style="font-family:var(--font-body); font-weight:800; text-transform:none; margin-bottom:14px;">Write a Review</h4>
        <form action="add_review.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
          <div class="field">
            <label>Your Rating</label>
            <div class="star-input">
              <input type="radio" name="rating" value="5" id="star5"><label for="star5">&#9733;</label>
              <input type="radio" name="rating" value="4" id="star4"><label for="star4">&#9733;</label>
              <input type="radio" name="rating" value="3" id="star3" checked><label for="star3">&#9733;</label>
              <input type="radio" name="rating" value="2" id="star2"><label for="star2">&#9733;</label>
              <input type="radio" name="rating" value="1" id="star1"><label for="star1">&#9733;</label>
            </div>
          </div>
          <div class="field">
            <label>Your Review</label>
            <textarea name="comment" required placeholder="What did you think of this product?"></textarea>
          </div>
          <button type="submit" class="btn btn-dark">Submit Review</button>
        </form>
      </div>
    <?php else: ?>
      <p style="margin-top:20px; color:var(--muted);"><a href="login.php" style="color:var(--accent); font-weight:700;">Log in</a> to write a review.</p>
    <?php endif; ?>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
