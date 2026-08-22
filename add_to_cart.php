<?php
require_once 'config/db.php';

$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

function respond($success, $message, $cart_count = null) {
    global $is_ajax;
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'message' => $message, 'cart_count' => $cart_count]);
        exit;
    } else {
        header("Location: " . ($success ? "cart.php" : "products.php"));
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $size = trim($_POST['size']);
    $color = trim($_POST['color']);
    $quantity = max(1, intval($_POST['quantity']));

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product) {
        respond(false, "Product not found.");
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $key = $product_id . '_' . $size . '_' . $color;
    $existing_qty = isset($_SESSION['cart'][$key]) ? $_SESSION['cart'][$key]['quantity'] : 0;

    if ($existing_qty + $quantity > $product['stock']) {
        respond(false, "Only " . $product['stock'] . " left in stock.");
    }

    if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$key] = [
            'product_id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'size' => $size,
            'color' => $color,
            'quantity' => $quantity,
        ];
    }

    $cart_count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }

    respond(true, "Added to cart!", $cart_count);
}

respond(false, "Invalid request.");
