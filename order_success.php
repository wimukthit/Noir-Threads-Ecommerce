<?php
require_once 'config/db.php';
$page_title = "Order Confirmed";

$order_id = $_SESSION['last_order_id'] ?? null;
if (!$order_id) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

require_once 'includes/header.php';
?>

<section class="section container">
  <div class="form-card" style="text-align:center;">
    <span class="section-eyebrow">Thank you</span>
    <h1>Order Confirmed 🎉</h1>
    <p class="form-sub">Your order <strong>#<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?></strong> has been placed successfully. We'll deliver it to <?php echo htmlspecialchars($order['city']); ?> soon.</p>
    <?php if ($order['discount'] > 0): ?>
      <p style="color:#2C6B2C; font-size:14px;">Coupon "<?php echo htmlspecialchars($order['coupon_code']); ?>" saved you Rs. <?php echo number_format($order['discount'], 2); ?>!</p>
    <?php endif; ?>
    <div class="summary-row total" style="justify-content:center; gap:10px; border:none;">
      <span>Total Paid:</span><span>Rs. <?php echo number_format($order['total'], 2); ?></span>
    </div>
    <br>
    <a href="products.php" class="btn btn-dark">Continue Shopping</a>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
