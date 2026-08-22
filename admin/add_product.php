<?php
require_once '../config/db.php';
require_once 'includes/admin_guard.php';
$page_title = "Add Product";

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
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id, image, sizes, colors, stock, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdisssii", $name, $description, $price, $category_id, $image, $sizes, $colors, $stock, $featured);
        $stmt->execute();
        $new_product_id = $conn->insert_id;

        if ($gallery_raw !== '') {
            $gallery_stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, sort_order) VALUES (?, ?, ?)");
            $lines = preg_split('/\r\n|\r|\n/', $gallery_raw);
            $order = 0;
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line !== '') {
                    $gallery_stmt->bind_param("isi", $new_product_id, $line, $order);
                    $gallery_stmt->execute();
                    $order++;
                }
            }
        }

        header("Location: products.php");
        exit;
    }
}

$categories = $conn->query("SELECT * FROM categories");
require_once 'includes/admin_header.php';
?>

<h1>Add Product</h1>
<p class="admin-sub">Fill in the details below to add a new t-shirt.</p>

<?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

<form method="post" style="max-width:600px;">
  <div class="field">
    <label>Product Name</label>
    <input type="text" name="name" required>
  </div>
  <div class="field">
    <label>Description</label>
    <textarea name="description"></textarea>
  </div>
  <div class="form-row">
    <div class="field">
      <label>Price (Rs.)</label>
      <input type="number" name="price" step="0.01" required>
    </div>
    <div class="field">
      <label>Stock</label>
      <input type="number" name="stock" value="50" required>
    </div>
  </div>
  <div class="field">
    <label>Category</label>
    <select name="category_id" required>
      <?php while ($c = $categories->fetch_assoc()): ?>
        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="field">
    <label>Image URL (main photo)</label>
    <input type="text" name="image" placeholder="https://..." required>
  </div>
  <div class="field">
    <label>Extra Gallery Photos (optional — one URL per line, 2–4 recommended)</label>
    <textarea name="gallery_images" rows="4" placeholder="https://example.com/photo2.jpg
https://example.com/photo3.jpg"></textarea>
  </div>
  <div class="form-row">
    <div class="field">
      <label>Sizes (comma separated)</label>
      <input type="text" name="sizes" value="S,M,L,XL" required>
    </div>
    <div class="field">
      <label>Colors (comma separated)</label>
      <input type="text" name="colors" value="Black,White" required>
    </div>
  </div>
  <div class="field">
    <label><input type="checkbox" name="featured" style="width:auto; display:inline-block; margin-right:8px;"> Show as Featured on homepage</label>
  </div>
  <button type="submit" class="btn btn-accent">Add Product</button>
</form>

<?php require_once 'includes/admin_footer.php'; ?>
