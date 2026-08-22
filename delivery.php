<?php
require_once 'config/db.php';
$page_title = "Delivery Info";
require_once 'includes/header.php';
?>

<section class="section container">
  <div class="form-card" style="max-width:640px;">
    <span class="section-eyebrow">Help</span>
    <h1>Delivery Information</h1>
    <p class="form-sub" style="margin-bottom:0;">Everything you need to know about getting your order.</p>

    <div style="margin-top:24px; line-height:1.8; color:var(--muted); font-size:14px;">
      <p><strong style="color:var(--ink);">Delivery Areas:</strong> We currently deliver island-wide across Sri Lanka.</p>
      <p><strong style="color:var(--ink);">Delivery Time:</strong> Orders are typically delivered within 3–5 working days. Colombo and suburbs may receive orders faster (1–3 days).</p>
      <p><strong style="color:var(--ink);">Delivery Charges:</strong> Free delivery on orders over Rs. 5,000. A flat Rs. 350 delivery fee applies to orders below that.</p>
      <p><strong style="color:var(--ink);">Order Tracking:</strong> Once logged in, you can check your order status anytime from the "My Orders" page — Pending, Processing, Shipped, or Delivered.</p>
      <p><strong style="color:var(--ink);">Payment on Delivery:</strong> We accept Cash on Delivery, Bank Transfer, and Card Payment (demo) at checkout.</p>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
