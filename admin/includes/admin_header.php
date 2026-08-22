<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: /tshirt-store/admin/login.php");
    exit;
}
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? $page_title . " | Admin" : "Admin"; ?></title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/tshirt-store/css/style.css">
<link rel="stylesheet" href="/tshirt-store/admin/css/admin.css">
</head>
<body>
<div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="logo" style="color:var(--bg); margin-bottom:40px;">NOIR<span>THREADS</span></div>
    <nav>
      <a href="dashboard.php" class="<?php echo $current == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
      <a href="products.php" class="<?php echo in_array($current, ['products.php','add_product.php','edit_product.php']) ? 'active' : ''; ?>">Products</a>
      <a href="categories.php" class="<?php echo $current == 'categories.php' ? 'active' : ''; ?>">Categories</a>
      <a href="orders.php" class="<?php echo $current == 'orders.php' ? 'active' : ''; ?>">Orders</a>
      <a href="reviews.php" class="<?php echo $current == 'reviews.php' ? 'active' : ''; ?>">Reviews</a>
      <a href="coupons.php" class="<?php echo $current == 'coupons.php' ? 'active' : ''; ?>">Coupons</a>
      <a href="messages.php" class="<?php echo $current == 'messages.php' ? 'active' : ''; ?>">Messages</a>
      <a href="settings.php" class="<?php echo $current == 'settings.php' ? 'active' : ''; ?>">Settings</a>
      <a href="/tshirt-store/index.php" target="_blank">View Store &#8599;</a>
      <a href="logout.php">Logout</a>
    </nav>
  </aside>
  <main class="admin-main">
