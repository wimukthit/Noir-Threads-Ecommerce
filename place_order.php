<?php
require_once 'config/db.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit;
}

$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$city = trim($_POST['city']);
$payment_method = $_POST['payment_method'];

$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery = ($subtotal > 0 && $subtotal < 5000) ? 350 : 0;

$coupon = $_SESSION['coupon'] ?? null;
$discount = 0;
$coupon_code = null;
if ($coupon) {
    $discount = $coupon['type'] === 'percent'
        ? round($subtotal * ($coupon['value'] / 100), 2)
        : min($coupon['value'], $subtotal);
    $coupon_code = $coupon['code'];
}
$total = $subtotal + $delivery - $discount;

$user_id = $_SESSION['user_id'] ?? null;

$stmt = $conn->prepare("INSERT INTO orders (user_id, full_name, email, phone, address, city, payment_method, total, status, coupon_code, discount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?, ?)");
$stmt->bind_param("issssssdsd", $user_id, $full_name, $email, $phone, $address, $city, $payment_method, $total, $coupon_code, $discount);
$stmt->execute();
$order_id = $conn->insert_id;

$item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, size, color, quantity, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stock_stmt = $conn->prepare("UPDATE products SET stock = GREATEST(stock - ?, 0) WHERE id = ?");

foreach ($cart as $item) {
    $item_stmt->bind_param("iisssid", $order_id, $item['product_id'], $item['name'], $item['size'], $item['color'], $item['quantity'], $item['price']);
    $item_stmt->execute();

    // Reduce stock for the purchased product
    $stock_stmt->bind_param("ii", $item['quantity'], $item['product_id']);
    $stock_stmt->execute();
}

// Clear cart and coupon after order is placed
unset($_SESSION['cart'], $_SESSION['coupon']);
$_SESSION['last_order_id'] = $order_id;

header("Location: order_success.php");
exit;
