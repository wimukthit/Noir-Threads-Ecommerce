<?php
require_once 'config/db.php';

$review_id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->bind_param("i", $review_id);
$stmt->execute();
$review = $stmt->get_result()->fetch_assoc();

if (!$review) {
    header("Location: products.php");
    exit;
}

// Only the review's own author (logged in) may delete it from this endpoint.
// Admins use admin/reviews.php's own delete handler.
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']) {
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
}

header("Location: product.php?id=" . $review['product_id'] . "#reviews");
exit;
