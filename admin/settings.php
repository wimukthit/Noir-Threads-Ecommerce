<?php
require_once '../config/db.php';
require_once 'includes/admin_guard.php';
$page_title = "Settings";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if (!password_verify($current_password, $admin['password'])) {
        $error = "Current password is incorrect.";
    } elseif (strlen($new_password) < 6) {
        $error = "New password must be at least 6 characters.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirmation don't match.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $_SESSION['admin_id']);
        $stmt->execute();
        $success = "Password updated successfully.";
    }
}

require_once 'includes/admin_header.php';
?>

<h1>Settings</h1>
<p class="admin-sub">Manage your admin account.</p>

<?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

<form method="post" style="max-width:420px;">
  <div class="field">
    <label>Current Password</label>
    <input type="password" name="current_password" required>
  </div>
  <div class="field">
    <label>New Password</label>
    <input type="password" name="new_password" required minlength="6">
  </div>
  <div class="field">
    <label>Confirm New Password</label>
    <input type="password" name="confirm_password" required minlength="6">
  </div>
  <button type="submit" class="btn btn-accent">Update Password</button>
</form>

<?php require_once 'includes/admin_footer.php'; ?>
