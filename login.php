<?php
require_once 'config/db.php';
$page_title = "Login";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

require_once 'includes/header.php';
?>

<section class="section container">
  <div class="form-card">
    <h1>Welcome back</h1>
    <p class="form-sub">Log in to track your orders and check out faster.</p>

    <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

    <form action="process_login.php" method="post">
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-accent btn-block">Log In</button>
    </form>

    <p class="form-note">Don't have an account? <a href="register.php">Sign up</a></p>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
