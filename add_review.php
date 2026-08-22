<?php
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: products.php");
    exit;
}

$product_id = intval($_POST['product_id']);
$rating = max(1, min(5, intval($_POST['rating'])));
$comment = trim($_POST['comment']);

$stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, user_name, rating, comment) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisis", $product_id, $_SESSION['user_id'], $_SESSION['user_name'], $rating, $comment);
$stmt->execute();

header("Location: product.php?id=" . $product_id . "#reviews");
exit;
