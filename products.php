<?php
require_once 'config/db.php';
$page_title = "Shop";

$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$size = isset($_GET['size']) ? $_GET['size'] : '';
$max_price = isset($_GET['max_price']) ? intval($_GET['max_price']) : 5000;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

$where_sql = "WHERE price <= ?";
$params = [$max_price];
$types = "i";

if ($category > 0) {
    $where_sql .= " AND category_id = ?";
    $params[] = $category;
    $types .= "i";
}
if ($size !== '') {
    $where_sql .= " AND FIND_IN_SET(?, sizes)";
    $params[] = $size;
    $types .= "s";
}
if ($q !== '') {
    $where_sql .= " AND (name LIKE ? OR description LIKE ?)";
    $like = '%' . $q . '%';
    $params[] = $like;
    $params[] = $like;
    $types .= "ss";
}

// Count total matches for pagination
$count_stmt = $conn->prepare("SELECT COUNT(*) AS c FROM products $where_sql");
$count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_assoc()['c'];
$total_pages = max(1, ceil($total / $per_page));

$order_sql = match ($sort) {
    'price_low' => " ORDER BY price ASC",
    'price_high' => " ORDER BY price DESC",
    default => " ORDER BY created_at DESC",
};

$sql = "SELECT * FROM products $where_sql $order_sql LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result();

$categories = $conn->query("SELECT * FROM categories");

// Build the base query string (without page) so pagination links keep filters
$base_params = $_GET;
unset($base_params['page']);
$base_query = http_build_query($base_params);

require_once 'includes/header.php';
?>

<section class="section container">
  <div class="section-head">
    <div>
      <span class="section-eyebrow">All Products</span>
      <h2>Shop the tees</h2>
    </div>
  </div>

  <div class="shop-layout">
    <!-- ============ FILTERS ============ -->
    <form class="filters" method="get" action="products.php">
      <h4>Filters</h4>

      <div class="filter-group">
        <label style="font-weight:700; text-transform:uppercase; font-size:12px; letter-spacing:0.5px; margin-bottom:10px;">Category</label>
        <label><input type="radio" name="category" value="0" <?php echo $category == 0 ? 'checked' : ''; ?>> All</label>
        <?php $categories->data_seek(0); while ($c = $categories->fetch_assoc()): ?>
          <label><input type="radio" name="category" value="<?php echo $c['id']; ?>" <?php echo $category == $c['id'] ? 'checked' : ''; ?>> <?php echo htmlspecialchars($c['name']); ?></label>
        <?php endwhile; ?>
      </div>

      <div class="filter-group">
        <label style="font-weight:700; text-transform:uppercase; font-size:12px; letter-spacing:0.5px; margin-bottom:10px;">Size</label>
        <label><input type="radio" name="size" value="" <?php echo $size == '' ? 'checked' : ''; ?>> All</label>
        <?php foreach (['XS','S','M','L','XL','XXL'] as $s): ?>
          <label><input type="radio" name="size" value="<?php echo $s; ?>" <?php echo $size == $s ? 'checked' : ''; ?>> <?php echo $s; ?></label>
        <?php endforeach; ?>
      </div>

      <div class="filter-group">
        <label style="font-weight:700; text-transform:uppercase; font-size:12px; letter-spacing:0.5px;">Max Price</label>
        <input type="range" name="max_price" min="1500" max="5000" step="100" value="<?php echo $max_price; ?>" oninput="this.nextElementSibling.innerText = 'Up to Rs. ' + this.value">
        <div class="price-value">Up to Rs. <?php echo $max_price; ?></div>
      </div>

      <div class="filter-group">
        <label style="font-weight:700; text-transform:uppercase; font-size:12px; letter-spacing:0.5px; margin-bottom:10px;">Sort By</label>
        <select name="sort" style="width:100%; padding:8px; border-radius:4px; border:1px solid var(--stone);">
          <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
          <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
          <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
        </select>
      </div>

      <button type="submit" class="btn btn-dark btn-block">Apply Filters</button>
    </form>

    <!-- ============ PRODUCT GRID ============ -->
    <div>
      <div class="results-bar">
        <span><?php echo $total; ?> products found<?php echo $q !== '' ? ' for "' . htmlspecialchars($q) . '"' : ''; ?></span>
      </div>

      <?php if ($products->num_rows === 0): ?>
        <div class="empty-state">
          <p>No products match these filters. Try widening your search.</p>
        </div>
      <?php else: ?>
        <div class="product-grid">
          <?php while ($p = $products->fetch_assoc()): ?>
            <a href="product.php?id=<?php echo $p['id']; ?>" class="product-card">
              <div class="product-thumb">
                <?php if ($p['featured']): ?><span class="product-tag">Featured</span><?php endif; ?>
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

        <?php if ($total_pages > 1): ?>
          <div class="pagination">
            <?php if ($page > 1): ?>
              <a href="products.php?<?php echo $base_query; ?>&page=<?php echo $page - 1; ?>" class="page-link">&larr; Prev</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <a href="products.php?<?php echo $base_query; ?>&page=<?php echo $i; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
              <a href="products.php?<?php echo $base_query; ?>&page=<?php echo $page + 1; ?>" class="page-link">Next &rarr;</a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
