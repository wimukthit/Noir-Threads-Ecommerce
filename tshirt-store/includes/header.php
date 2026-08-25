<?php
// Cart count eka calculate karanna
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? $page_title . " | NOIR THREADS" : "NOIR THREADS — Tees, done right"; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/tshirt-store/css/base.css">
<?php if (!empty($page_css)): foreach ($page_css as $css): ?>
<link rel="stylesheet" href="/tshirt-store/css/<?php echo htmlspecialchars($css); ?>">
<?php endforeach; endif; ?>
</head>
<body>

<!-- MARQUEE BANNER -->
<div class="marquee">
  <div class="marquee-track">
    <span>FREE DELIVERY OVER RS. 5000 &nbsp;&bull;&nbsp; NEW DROP EVERY MONTH &nbsp;&bull;&nbsp; LIMITED STOCK &nbsp;&bull;&nbsp; 100% COTTON &nbsp;&bull;&nbsp; FREE DELIVERY OVER RS. 5000 &nbsp;&bull;&nbsp; NEW DROP EVERY MONTH &nbsp;&bull;&nbsp; LIMITED STOCK &nbsp;&bull;&nbsp; 100% COTTON &nbsp;&bull;&nbsp;</span>
  </div>
</div>

<!-- HEADER -->
<header class="site-header">
  <div class="header-inner">
    <a href="/tshirt-store/index.php" class="logo">NOIR<span>THREADS</span></a>
    <nav class="main-nav">
      <a href="/tshirt-store/index.php">Home</a>
      <a href="/tshirt-store/products.php">Shop</a>
      <a href="/tshirt-store/products.php?category=1">Men</a>
      <a href="/tshirt-store/products.php?category=2">Women</a>
      <a href="/tshirt-store/products.php?category=4">Kids</a>
    </nav>
    <div class="header-actions">
      <a href="/tshirt-store/cart.php" class="cart-link">
        Cart <span class="cart-badge"><?php echo $cart_count; ?></span>
      </a>
    </div>
  </div>
</header>

<!-- TOAST CONTAINER -->
<div id="toast-container" class="toast-container"></div>