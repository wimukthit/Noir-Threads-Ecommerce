<?php
require_once '../config/db.php';
require_once 'includes/admin_guard.php';
$page_title = "Categories";

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $name = trim($_POST['name']);
        if ($name === '') {
            $error = "Category name can't be empty.";
        } else {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            header("Location: categories.php");
            exit;
        }
    } elseif ($_POST['action'] === 'rename') {
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        if ($name !== '') {
            $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $id);
            $stmt->execute();
        }
        header("Location: categories.php");
        exit;
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $in_use = $conn->query("SELECT COUNT(*) AS c FROM products WHERE category_id = $id")->fetch_assoc()['c'];
    if ($in_use > 0) {
        $error = "Can't delete this category — $in_use product(s) still use it. Move or delete those products first.";
    } else {
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: categories.php");
        exit;
    }
}

require_once 'includes/admin_header.php';

$categories = $conn->query("
    SELECT c.*, COUNT(p.id) AS product_count
    FROM categories c
    LEFT JOIN products p ON p.category_id = c.id
    GROUP BY c.id
    ORDER BY c.name ASC
");
?>

<h1>Categories</h1>
<p class="admin-sub">Organize your products into categories like Men, Women, Kids, Unisex.</p>

<?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

<form method="post" style="max-width:420px; display:flex; gap:10px; margin-bottom:32px;">
  <input type="hidden" name="action" value="add">
  <input type="text" name="name" placeholder="New category name" required style="flex:1; padding:12px 14px; border:1px solid var(--stone); border-radius:4px;">
  <button type="submit" class="btn btn-accent">Add</button>
</form>

<table class="data-table">
  <thead>
    <tr><th>Name</th><th>Products</th><th></th></tr>
  </thead>
  <tbody>
    <?php while ($c = $categories->fetch_assoc()): ?>
      <tr>
        <td>
          <form method="post" style="display:flex; gap:8px; align-items:center;">
            <input type="hidden" name="action" value="rename">
            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
            <input type="text" name="name" value="<?php echo htmlspecialchars($c['name']); ?>" style="padding:6px 10px; border:1px solid var(--stone); border-radius:4px; font-size:14px;">
            <button type="submit" class="btn btn-outline" style="padding:6px 14px; font-size:11px;">Save</button>
          </form>
        </td>
        <td><?php echo $c['product_count']; ?></td>
        <td class="table-actions">
          <a href="categories.php?delete=<?php echo $c['id']; ?>" class="danger" onclick="return confirm('Delete this category?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php require_once 'includes/admin_footer.php'; ?>
