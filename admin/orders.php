<?php
require_once '../config/db.php';
require_once 'includes/admin_guard.php';
$page_title = "Orders";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $_POST['status'], $_POST['order_id']);
    $stmt->execute();
}

require_once 'includes/admin_header.php';

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
$statuses = ['Pending', 'Processing', 'Shipped', 'Delivered'];
?>

<h1>Orders</h1>
<p class="admin-sub">Track and update customer orders.</p>

<table class="data-table">
  <thead>
    <tr><th>Order</th><th>Customer</th><th>Phone</th><th>City</th><th>Total</th><th>Status</th><th>Date</th></tr>
  </thead>
  <tbody>
    <?php while ($o = $orders->fetch_assoc()): ?>
      <tr>
        <td>#<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?></td>
        <td><?php echo htmlspecialchars($o['full_name']); ?></td>
        <td><?php echo htmlspecialchars($o['phone']); ?></td>
        <td><?php echo htmlspecialchars($o['city']); ?></td>
        <td>Rs. <?php echo number_format($o['total'], 2); ?></td>
        <td>
          <form method="post" style="display:flex; gap:6px; align-items:center;">
            <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
            <select name="status" onchange="this.form.submit()" style="padding:6px; border-radius:4px; border:1px solid var(--stone);">
              <?php foreach ($statuses as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo $o['status'] == $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
              <?php endforeach; ?>
            </select>
          </form>
        </td>
        <td><?php echo date("d M Y", strtotime($o['created_at'])); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php require_once 'includes/admin_footer.php'; ?>
