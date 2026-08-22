<?php
require_once '../config/db.php';
require_once 'includes/admin_guard.php';
$page_title = "Messages";

if (isset($_GET['mark_read'])) {
    $stmt = $conn->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $_GET['mark_read']);
    $stmt->execute();
    header("Location: messages.php");
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: messages.php");
    exit;
}

require_once 'includes/admin_header.php';

$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>

<h1>Messages</h1>
<p class="admin-sub">Contact form submissions from customers.</p>

<?php if ($messages->num_rows === 0): ?>
  <div class="empty-state">
    <p>No messages yet.</p>
  </div>
<?php else: ?>
  <?php while ($m = $messages->fetch_assoc()): ?>
    <div class="order-card" style="<?php echo $m['is_read'] ? '' : 'border-color:var(--accent);'; ?>">
      <div class="order-card-head">
        <span><strong style="color:var(--ink);"><?php echo htmlspecialchars($m['name']); ?></strong> &lt;<?php echo htmlspecialchars($m['email']); ?>&gt; &bull; <?php echo date("d M Y, g:i A", strtotime($m['created_at'])); ?></span>
        <?php if (!$m['is_read']): ?><span class="status-pill status-Pending">New</span><?php endif; ?>
      </div>
      <p style="color:var(--muted); font-size:14px; line-height:1.6; margin:10px 0;"><?php echo nl2br(htmlspecialchars($m['message'])); ?></p>
      <div class="table-actions">
        <?php if (!$m['is_read']): ?><a href="messages.php?mark_read=<?php echo $m['id']; ?>">Mark as Read</a><?php endif; ?>
        <a href="messages.php?delete=<?php echo $m['id']; ?>" class="danger" onclick="return confirm('Delete this message?');">Delete</a>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>

<?php require_once 'includes/admin_footer.php'; ?>
