<?php
require_once 'config/db.php';
$page_title = "Sign Up";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);

require_once 'includes/header.php';
?>

<section class="section container">
  <div class="form-card">
    <h1>Create an account</h1>
    <p class="form-sub">Sign up to save your details and track orders.</p>

    <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

    <form action="process_register.php" method="post">
      <div class="field">
        <label>Full Name</label>
        <input type="text" name="name" required>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" required minlength="6">
      </div>
      <button type="submit" class="btn btn-accent btn-block">Sign Up</button>
    </form>

    <p class="form-note">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
