<?php
require_once 'config/db.php';
$page_title = "Your Cart";

$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery = ($subtotal > 0 && $subtotal < 5000) ? 350 : 0;

$coupon = $_SESSION['coupon'] ?? null;
$discount = 0;
if ($coupon) {
    $discount = $coupon['type'] === 'percent'
        ? round($subtotal * ($coupon['value'] / 100), 2)
        : min($coupon['value'], $subtotal);
}
$total = $subtotal + $delivery - $discount;

$coupon_error = $_SESSION['coupon_error'] ?? '';
unset($_SESSION['coupon_error']);

require_once 'includes/header.php';
?>

<section class="section container">
  <div class="section-head">
    <div>
      <span class="section-eyebrow">Step 1 of 2</span>
      <h2>Your Cart</h2>
    </div>
  </div>

  <?php if (empty($cart)): ?>
    <div class="empty-state">
      <p>Your cart is empty.</p>
      <br>
      <a href="products.php" class="btn btn-dark">Continue Shopping</a>
    </div>
  <?php else: ?>
    <div class="cart-layout">
      <form action="update_cart.php" method="post">
        <table class="cart-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cart as $key => $item): ?>
              <tr>
                <td>
                  <div class="cart-item-info">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div>
                      <div><?php echo htmlspecialchars($item['name']); ?></div>
                      <div class="cart-item-meta">Size: <?php echo htmlspecialchars($item['size']); ?> &bull; Color: <?php echo htmlspecialchars($item['color']); ?></div>
                      <a href="remove_from_cart.php?key=<?php echo urlencode($key); ?>" class="remove-link">Remove</a>
                    </div>
                  </div>
                </td>
                <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                <td>
                  <input type="number" name="quantity[<?php echo htmlspecialchars($key); ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="20" style="width:60px; padding:6px; border:1px solid var(--stone); border-radius:4px;">
                </td>
                <td>Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br>
        <button type="submit" class="btn btn-outline">Update Cart</button>
      </form>

      <div class="summary-box">
        <h3>Order Summary</h3>

        <?php if ($coupon): ?>
          <div class="coupon-applied">
            <span>Coupon "<?php echo htmlspecialchars($coupon['code']); ?>" applied</span>
            <a href="remove_coupon.php?redirect=cart.php">Remove</a>
          </div>
        <?php else: ?>
          <?php if ($coupon_error): ?><div class="alert alert-error" style="padding:10px 14px; font-size:13px;"><?php echo htmlspecialchars($coupon_error); ?></div><?php endif; ?>
          <form action="apply_coupon.php" method="post" class="coupon-box">
            <input type="hidden" name="redirect" value="cart.php">
            <input type="text" name="coupon_code" placeholder="Coupon code">
            <button type="submit" class="btn btn-outline">Apply</button>
          </form>
        <?php endif; ?>

        <div class="summary-row"><span>Subtotal</span><span>Rs. <?php echo number_format($subtotal, 2); ?></span></div>
        <?php if ($discount > 0): ?>
          <div class="summary-row"><span>Discount</span><span>&minus; Rs. <?php echo number_format($discount, 2); ?></span></div>
        <?php endif; ?>
        <div class="summary-row"><span>Delivery</span><span><?php echo $delivery > 0 ? 'Rs. ' . number_format($delivery, 2) : 'Free'; ?></span></div>
        <div class="summary-row total"><span>Total</span><span>Rs. <?php echo number_format($total, 2); ?></span></div>
        <br>
        <a href="checkout.php" class="btn btn-accent btn-block">Proceed to Checkout</a>
      </div>
    </div>
  <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>
