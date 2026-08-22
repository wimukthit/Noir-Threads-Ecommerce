<?php
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$product_id = intval($_GET['product_id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM product_images WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: edit_product.php?id=" . $product_id);
exit;
