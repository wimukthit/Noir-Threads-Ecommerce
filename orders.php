<?php
require_once 'config/db.php';
$page_title = "My Orders";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$orders = $stmt->get_result();

require_once 'includes/header.php';
?>

<section class="section container">
  <div class="section-head">
    <div>
      <span class="section-eyebrow">Account</span>
      <h2>My Orders</h2>
    </div>
  </div>

  <?php if ($orders->num_rows === 0): ?>
    <div class="empty-state">
      <p>You haven't placed any orders yet.</p>
      <br>
      <a href="products.php" class="btn btn-dark">Start Shopping</a>
    </div>
  <?php else: ?>
    <?php while ($o = $orders->fetch_assoc()): ?>
      <?php
        $items_stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $items_stmt->bind_param("i", $o['id']);
        $items_stmt->execute();
        $items = $items_stmt->get_result();
      ?>
      <div class="order-card">
        <div class="order-card-head">
          <span>Order #<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?> &bull; <?php echo date("d M Y", strtotime($o['created_at'])); ?></span>
          <span class="status-pill status-<?php echo str_replace(' ', '', $o['status']); ?>"><?php echo htmlspecialchars($o['status']); ?></span>
        </div>
        <?php while ($item = $items->fetch_assoc()): ?>
          <div style="display:flex; justify-content:space-between; font-size:14px; padding:6px 0; color:var(--muted);">
            <span><?php echo htmlspecialchars($item['product_name']); ?> (<?php echo htmlspecialchars($item['size']); ?>, <?php echo htmlspecialchars($item['color']); ?>) x<?php echo $item['quantity']; ?></span>
            <span>Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
          </div>
        <?php endwhile; ?>
        <div style="text-align:right; font-weight:800; margin-top:10px; border-top:1px solid var(--stone); padding-top:10px;">
          Total: Rs. <?php echo number_format($o['total'], 2); ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>
