<?php
require_once '../config/db.php';
require_once 'includes/admin_guard.php';
$page_title = "Edit Product";

$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $image = trim($_POST['image']);
    $sizes = trim($_POST['sizes']);
    $colors = trim($_POST['colors']);
    $stock = intval($_POST['stock']);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $gallery_raw = trim($_POST['gallery_images'] ?? '');

    if ($name === '' || $price <= 0) {
        $error = "Please fill in the product name and a valid price.";
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, category_id=?, image=?, sizes=?, colors=?, stock=?, featured=? WHERE id=?");
        $stmt->bind_param("ssdisssiii", $name, $description, $price, $category_id, $image, $sizes, $colors, $stock, $featured, $id);
        $stmt->execute();

        if ($gallery_raw !== '') {
            $gallery_stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, sort_order) VALUES (?, ?, ?)");
            $max_order = $conn->query("SELECT COALESCE(MAX(sort_order),-1) AS m FROM product_images WHERE product_id = $id")->fetch_assoc()['m'];
            $order = $max_order + 1;
            $lines = preg_split('/\r\n|\r|\n/', $gallery_raw);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line !== '') {
                    $gallery_stmt->bind_param("isi", $id, $line, $order);
                    $gallery_stmt->execute();
                    $order++;
                }
            }
        }

        header("Location: edit_product.php?id=" . $id);
        exit;
    }
}

$categories = $conn->query("SELECT * FROM categories");
$gallery = $conn->query("SELECT * FROM product_images WHERE product_id = $id ORDER BY sort_order ASC");
require_once 'includes/admin_header.php';
?>

<h1>Edit Product</h1>
<p class="admin-sub">Update the details for "<?php echo htmlspecialchars($product['name']); ?>".</p>

<?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

<form method="post" style="max-width:600px;">
  <div class="field">
    <label>Product Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
  </div>
  <div class="field">
    <label>Description</label>
    <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
  </div>
  <div class="form-row">
    <div class="field">
      <label>Price (Rs.)</label>
      <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
    </div>
    <div class="field">
      <label>Stock</label>
      <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>
    </div>
  </div>
  <div class="field">
    <label>Category</label>
    <select name="category_id" required>
      <?php $categories->data_seek(0); while ($c = $categories->fetch_assoc()): ?>
        <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $product['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="field">
    <label>Image URL (main photo)</label>
    <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required>
  </div>

  <?php if ($gallery->num_rows > 0): ?>
    <div class="field">
      <label>Current Gallery Photos</label>
      <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <?php $gallery->data_seek(0); while ($g = $gallery->fetch_assoc()): ?>
          <div style="text-align:center;">
            <img src="<?php echo htmlspecialchars($g['image_url']); ?>" style="width:70px; height:84px; object-fit:cover; border-radius:4px; border:1px solid var(--stone);">
            <a href="delete_product_image.php?id=<?php echo $g['id']; ?>&product_id=<?php echo $id; ?>" class="danger" style="display:block; font-size:11px; margin-top:4px;" onclick="return confirm('Remove this photo?');">Remove</a>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="field">
    <label>Add More Gallery Photos (optional — one URL per line)</label>
    <textarea name="gallery_images" rows="3" placeholder="https://example.com/photo2.jpg"></textarea>
  </div>
  <div class="form-row">
    <div class="field">
      <label>Sizes (comma separated)</label>
      <input type="text" name="sizes" value="<?php echo htmlspecialchars($product['sizes']); ?>" required>
    </div>
    <div class="field">
      <label>Colors (comma separated)</label>
      <input type="text" name="colors" value="<?php echo htmlspecialchars($product['colors']); ?>" required>
    </div>
  </div>
  <div class="field">
    <label><input type="checkbox" name="featured" style="width:auto; display:inline-block; margin-right:8px;" <?php echo $product['featured'] ? 'checked' : ''; ?>> Show as Featured on homepage</label>
  </div>
  <button type="submit" class="btn btn-accent">Save Changes</button>
</form>

<?php require_once 'includes/admin_footer.php'; ?>
