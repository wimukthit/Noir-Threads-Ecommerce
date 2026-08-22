<?php
require_once '../config/db.php';
$page_title = "Reviews";
require_once 'includes/admin_header.php';

$reviews = $conn->query("
    SELECT r.*, p.name AS product_name
    FROM reviews r
    LEFT JOIN products p ON r.product_id = p.id
    ORDER BY r.created_at DESC
");

function admin_star_string($rating) {
    $out = '<span class="stars">';
    for ($i = 1; $i <= 5; $i++) {
        $out .= '<span class="' . ($i <= $rating ? 'filled' : '') . '">&#9733;</span>';
    }
    $out .= '</span>';
    return $out;
}
?>

<h1>Reviews</h1>
<p class="admin-sub">Moderate customer reviews — remove anything inappropriate or spammy.</p>

<table class="data-table">
  <thead>
    <tr><th>Product</th><th>Customer</th><th>Rating</th><th>Comment</th><th>Date</th><th></th></tr>
  </thead>
  <tbody>
    <?php if ($reviews->num_rows === 0): ?>
      <tr><td colspan="6" style="text-align:center; color:var(--muted); padding:24px;">No reviews yet.</td></tr>
    <?php endif; ?>
    <?php while ($r = $reviews->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($r['product_name'] ?? 'Deleted product'); ?></td>
        <td><?php echo htmlspecialchars($r['user_name']); ?></td>
        <td><?php echo admin_star_string($r['rating']); ?></td>
        <td style="max-width:320px;"><?php echo htmlspecialchars($r['comment']); ?></td>
        <td><?php echo date("d M Y", strtotime($r['created_at'])); ?></td>
        <td class="table-actions">
          <a href="delete_review.php?id=<?php echo $r['id']; ?>" class="danger" onclick="return confirm('Delete this review?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php require_once 'includes/admin_footer.php'; ?>
