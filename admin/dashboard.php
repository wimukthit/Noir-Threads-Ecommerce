<?php
require_once '../config/db.php';
$page_title = "Dashboard";
require_once 'includes/admin_header.php';

$total_products = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$total_orders = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$total_revenue = $conn->query("SELECT COALESCE(SUM(total),0) AS s FROM orders")->fetch_assoc()['s'];
$recent_orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
$low_stock = $conn->query("SELECT COUNT(*) AS c FROM products WHERE stock <= 10")->fetch_assoc()['c'];
$unread_messages = $conn->query("SELECT COUNT(*) AS c FROM contact_messages WHERE is_read = 0")->fetch_assoc()['c'];

$top_products = $conn->query("
    SELECT product_name, SUM(quantity) AS total_sold, SUM(quantity * price) AS total_revenue
    FROM order_items
    GROUP BY product_name
    ORDER BY total_sold DESC
    LIMIT 5
");

// Sales for the last 6 months, oldest first
$sales_raw = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, SUM(total) AS revenue
    FROM orders
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY ym
    ORDER BY ym ASC
");

$chart_labels = [];
$chart_data = [];
$monthly = [];
while ($row = $sales_raw->fetch_assoc()) {
    $monthly[$row['ym']] = (float) $row['revenue'];
}
// Fill in all 6 months even if a month had zero sales
for ($i = 5; $i >= 0; $i--) {
    $ym = date('Y-m', strtotime("-$i months"));
    $chart_labels[] = date('M Y', strtotime("-$i months"));
    $chart_data[] = $monthly[$ym] ?? 0;
}
?>

<h1>Dashboard</h1>
<p class="admin-sub">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>.</p>

<div class="stat-cards">
  <div class="stat-card">
    <div class="value"><?php echo $total_products; ?></div>
    <div class="label">Total Products</div>
  </div>
  <div class="stat-card">
    <div class="value"><?php echo $total_orders; ?></div>
    <div class="label">Total Orders</div>
  </div>
  <div class="stat-card">
    <div class="value">Rs. <?php echo number_format($total_revenue, 0); ?></div>
    <div class="label">Total Revenue</div>
  </div>
</div>

<?php if ($low_stock > 0): ?>
  <div class="alert alert-error" style="margin-bottom:16px;">
    <?php echo $low_stock; ?> product<?php echo $low_stock == 1 ? '' : 's'; ?> running low on stock (10 or fewer left). Check the Products page.
  </div>
<?php endif; ?>

<?php if ($unread_messages > 0): ?>
  <div class="alert alert-success" style="margin-bottom:28px; background:#DCEBFF; color:#1D4E8A; border-color:#B8D4F5;">
    <?php echo $unread_messages; ?> new customer message<?php echo $unread_messages == 1 ? '' : 's'; ?> waiting. Check the Messages page.
  </div>
<?php endif; ?>

<div class="admin-top-row"><h3 style="font-family:var(--font-body); font-weight:800; font-size:18px;">Top Selling Products</h3></div>
<table class="data-table" style="margin-bottom:36px;">
  <thead>
    <tr><th>Product</th><th>Units Sold</th><th>Revenue</th></tr>
  </thead>
  <tbody>
    <?php if ($top_products->num_rows === 0): ?>
      <tr><td colspan="3" style="text-align:center; color:var(--muted); padding:20px;">No sales yet.</td></tr>
    <?php endif; ?>
    <?php while ($tp = $top_products->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($tp['product_name']); ?></td>
        <td><?php echo $tp['total_sold']; ?></td>
        <td>Rs. <?php echo number_format($tp['total_revenue'], 2); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<div class="admin-top-row"><h3 style="font-family:var(--font-body); font-weight:800; font-size:18px;">Sales — Last 6 Months</h3></div>
<div style="border:1px solid var(--stone); border-radius:6px; padding:20px; background:var(--white); margin-bottom:36px;">
  <canvas id="salesChart" height="90"></canvas>
</div>

<div class="admin-top-row"><h3 style="font-family:var(--font-body); font-weight:800; font-size:18px;">Recent Orders</h3></div>
<table class="data-table">
  <thead>
    <tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
  </thead>
  <tbody>
    <?php while ($o = $recent_orders->fetch_assoc()): ?>
      <tr>
        <td>#<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?></td>
        <td><?php echo htmlspecialchars($o['full_name']); ?></td>
        <td>Rs. <?php echo number_format($o['total'], 2); ?></td>
        <td><span class="status-pill status-<?php echo str_replace(' ', '', $o['status']); ?>"><?php echo htmlspecialchars($o['status']); ?></span></td>
        <td><?php echo date("d M Y", strtotime($o['created_at'])); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($chart_labels); ?>,
    datasets: [{
      label: 'Revenue (Rs.)',
      data: <?php echo json_encode($chart_data); ?>,
      borderColor: '#FF4E32',
      backgroundColor: 'rgba(255, 78, 50, 0.1)',
      borderWidth: 2,
      tension: 0.3,
      fill: true,
      pointBackgroundColor: '#16151A',
      pointRadius: 4,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, ticks: { callback: v => 'Rs. ' + v.toLocaleString() } }
    }
  }
});
</script>

<?php require_once 'includes/admin_footer.php'; ?>
