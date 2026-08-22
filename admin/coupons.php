<?php
require_once '../config/db.php';
require_once 'includes/admin_guard.php';
$page_title = "Coupons";

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $code = strtoupper(trim($_POST['code']));
    $discount_type = $_POST['discount_type'] === 'fixed' ? 'fixed' : 'percent';
    $discount_value = floatval($_POST['discount_value']);
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null;

    if ($code === '' || $discount_value <= 0) {
        $error = "Please enter a valid code and discount value.";
    } else {
        $stmt = $conn->prepare("INSERT INTO coupons (code, discount_type, discount_value, active, expiry_date) VALUES (?, ?, ?, 1, ?)");
        $stmt->bind_param("ssds", $code, $discount_type, $discount_value, $expiry_date);
        if (!$stmt->execute()) {
            $error = "That coupon code already exists.";
        } else {
            header("Location: coupons.php");
            exit;
        }
    }
}

if (isset($_GET['toggle'])) {
    $stmt = $conn->prepare("UPDATE coupons SET active = NOT active WHERE id = ?");
    $stmt->bind_param("i", $_GET['toggle']);
    $stmt->execute();
    header("Location: coupons.php");
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM coupons WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: coupons.php");
    exit;
}

require_once 'includes/admin_header.php';
$coupons = $conn->query("SELECT * FROM coupons ORDER BY id DESC");
?>

<h1>Coupons</h1>
<p class="admin-sub">Create discount codes customers can use at checkout.</p>

<?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

<form method="post" style="max-width:600px; border:1px solid var(--stone); border-radius:6px; padding:20px; margin-bottom:32px; background:var(--white);">
  <input type="hidden" name="action" value="add">
  <div class="form-row">
    <div class="field">
      <label>Coupon Code</label>
      <input type="text" name="code" placeholder="e.g. SUMMER20" required>
    </div>
    <div class="field">
      <label>Discount Type</label>
      <select name="discount_type">
        <option value="percent">Percentage (%)</option>
        <option value="fixed">Fixed Amount (Rs.)</option>
      </select>
    </div>
  </div>
  <div class="form-row">
    <div class="field">
      <label>Discount Value</label>
      <input type="number" name="discount_value" step="0.01" required>
    </div>
    <div class="field">
      <label>Expiry Date (optional)</label>
      <input type="date" name="expiry_date">
    </div>
  </div>
  <button type="submit" class="btn btn-accent">Add Coupon</button>
</form>

<table class="data-table">
  <thead>
    <tr><th>Code</th><th>Discount</th><th>Expiry</th><th>Status</th><th></th></tr>
  </thead>
  <tbody>
    <?php if ($coupons->num_rows === 0): ?>
      <tr><td colspan="5" style="text-align:center; color:var(--muted); padding:24px;">No coupons yet.</td></tr>
    <?php endif; ?>
    <?php while ($c = $coupons->fetch_assoc()): ?>
      <tr>
        <td><strong><?php echo htmlspecialchars($c['code']); ?></strong></td>
        <td><?php echo $c['discount_type'] === 'percent' ? $c['discount_value'] . '%' : 'Rs. ' . number_format($c['discount_value'], 2); ?></td>
        <td><?php echo $c['expiry_date'] ? date("d M Y", strtotime($c['expiry_date'])) : 'No expiry'; ?></td>
        <td><span class="status-pill <?php echo $c['active'] ? 'status-Delivered' : 'status-Pending'; ?>"><?php echo $c['active'] ? 'Active' : 'Inactive'; ?></span></td>
        <td class="table-actions">
          <a href="coupons.php?toggle=<?php echo $c['id']; ?>"><?php echo $c['active'] ? 'Deactivate' : 'Activate'; ?></a>
          <a href="coupons.php?delete=<?php echo $c['id']; ?>" class="danger" onclick="return confirm('Delete this coupon?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php require_once 'includes/admin_footer.php'; ?>
