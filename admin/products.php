<?php
require_once '../config/db.php';
$page_title = "Products";
require_once 'includes/admin_header.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$where = '';
$params = [];
$types = '';
if ($search !== '') {
    $where = "WHERE p.name LIKE ?";
    $params[] = '%' . $search . '%';
    $types .= 's';
}

$count_sql = "SELECT COUNT(*) AS c FROM products p $where";
if ($search !== '') {
    $cstmt = $conn->prepare($count_sql);
    $cstmt->bind_param($types, ...$params);
    $cstmt->execute();
    $total = $cstmt->get_result()->fetch_assoc()['c'];
} else {
    $total = $conn->query($count_sql)->fetch_assoc()['c'];
}
$total_pages = max(1, ceil($total / $per_page));

$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id $where ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result();
?>

<div class="admin-top-row">
  <div>
    <h1>Products</h1>
    <p class="admin-sub">Manage everything in your catalog (<?php echo $total; ?> total).</p>
  </div>
  <a href="add_product.php" class="btn btn-accent">+ Add Product</a>
</div>

<form method="get" style="max-width:320px; margin-bottom:20px; display:flex; gap:8px;">
  <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>" style="flex:1; padding:10px 14px; border:1px solid var(--stone); border-radius:4px;">
  <button type="submit" class="btn btn-outline">Search</button>
</form>

<table class="data-table">
  <thead>
    <tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Featured</th><th>Actions</th></tr>
  </thead>
  <tbody>
    <?php if ($products->num_rows === 0): ?>
      <tr><td colspan="7" style="text-align:center; color:var(--muted); padding:24px;">No products found.</td></tr>
    <?php endif; ?>
    <?php while ($p = $products->fetch_assoc()): ?>
      <tr class="<?php echo $p['stock'] <= 10 ? 'low-stock-row' : ''; ?>">
        <td><img src="<?php echo htmlspecialchars($p['image']); ?>" class="table-thumb"></td>
        <td><?php echo htmlspecialchars($p['name']); ?></td>
        <td><?php echo htmlspecialchars($p['category_name']); ?></td>
        <td>Rs. <?php echo number_format($p['price'], 2); ?></td>
        <td>
          <?php echo $p['stock']; ?>
          <?php if ($p['stock'] <= 0): ?>
            <span class="stock-badge out">Out</span>
          <?php elseif ($p['stock'] <= 10): ?>
            <span class="stock-badge low">Low</span>
          <?php endif; ?>
        </td>
        <td><?php echo $p['featured'] ? 'Yes' : 'No'; ?></td>
        <td class="table-actions">
          <a href="edit_product.php?id=<?php echo $p['id']; ?>">Edit</a>
          <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="danger" onclick="return confirm('Delete this product?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php if ($total_pages > 1): ?>
  <div style="display:flex; gap:8px; margin-top:20px;">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <a href="products.php?page=<?php echo $i; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>"
         class="btn <?php echo $i == $page ? 'btn-dark' : 'btn-outline'; ?>" style="padding:8px 16px; font-size:13px;">
        <?php echo $i; ?>
      </a>
    <?php endfor; ?>
  </div>
<?php endif; ?>

<?php require_once 'includes/admin_footer.php'; ?>
