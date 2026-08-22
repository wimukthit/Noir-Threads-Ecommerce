<?php
require_once 'config/db.php';
$page_title = "Checkout";

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

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

$name = $_SESSION['user_name'] ?? '';
$email = $_SESSION['user_email'] ?? '';

require_once 'includes/header.php';
?>

<section class="section container">
  <div class="section-head">
    <div>
      <span class="section-eyebrow">Step 2 of 2</span>
      <h2>Checkout</h2>
    </div>
  </div>

  <div class="cart-layout">
    <form action="place_order.php" method="post" class="form-card" style="max-width:none;">
      <h1 style="font-size:20px; margin-bottom:20px;">Delivery Details</h1>

      <div class="field">
        <label>Full Name</label>
        <input type="text" name="full_name" required value="<?php echo htmlspecialchars($name); ?>">
      </div>

      <div class="form-row">
        <div class="field">
          <label>Email</label>
          <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
        </div>
        <div class="field">
          <label>Phone</label>
          <input type="tel" id="phoneInput" name="phone" required placeholder="07X XXX XXXX">
        </div>
      </div>

      <div class="field">
        <label>Delivery Address</label>
        <textarea name="address" required placeholder="House No, Street, Area"></textarea>
      </div>

      <div class="field">
        <label>City</label>
        <input type="text" name="city" required>
      </div>

      <fieldset>
        <legend>Payment Method</legend>
        <label class="radio-row"><input type="radio" name="payment_method" value="Cash on Delivery" checked> Cash on Delivery</label>
        <label class="radio-row"><input type="radio" name="payment_method" value="Bank Transfer"> Bank Transfer</label>
        <label class="radio-row"><input type="radio" name="payment_method" value="Card Payment"> Card Payment (demo only)</label>
      </fieldset>

      <button type="submit" class="btn btn-accent btn-block">Place Order — Rs. <?php echo number_format($total, 2); ?></button>
    </form>

    <div class="summary-box">
      <h3>Order Summary</h3>

      <?php if ($coupon): ?>
        <div class="coupon-applied">
          <span>Coupon "<?php echo htmlspecialchars($coupon['code']); ?>" applied</span>
          <a href="remove_coupon.php?redirect=checkout.php">Remove</a>
        </div>
      <?php else: ?>
        <?php if ($coupon_error): ?><div class="alert alert-error" style="padding:10px 14px; font-size:13px;"><?php echo htmlspecialchars($coupon_error); ?></div><?php endif; ?>
        <form action="apply_coupon.php" method="post" class="coupon-box">
          <input type="hidden" name="redirect" value="checkout.php">
          <input type="text" name="coupon_code" placeholder="Coupon code">
          <button type="submit" class="btn btn-outline">Apply</button>
        </form>
      <?php endif; ?>

      <?php foreach ($cart as $item): ?>
        <div class="summary-row">
          <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span>
          <span>Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
        </div>
      <?php endforeach; ?>
      <?php if ($discount > 0): ?>
        <div class="summary-row"><span>Discount</span><span>&minus; Rs. <?php echo number_format($discount, 2); ?></span></div>
      <?php endif; ?>
      <div class="summary-row"><span>Delivery</span><span><?php echo $delivery > 0 ? 'Rs. ' . number_format($delivery, 2) : 'Free'; ?></span></div>
      <div class="summary-row total"><span>Total</span><span>Rs. <?php echo number_format($total, 2); ?></span></div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
