<?php
require_once 'config/db.php';
$page_title = "Contact Us";

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($name !== '' && $email !== '' && $message !== '') {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        $stmt->execute();
        $success = true;
    }
}

require_once 'includes/header.php';
?>

<section class="section container">
  <div class="form-card">
    <span class="section-eyebrow">Help</span>
    <h1>Contact Us</h1>
    <p class="form-sub">Questions about an order, a product, or anything else? Send us a message.</p>

    <?php if ($success): ?>
      <div class="alert alert-success">Thanks! Your message has been sent — we'll get back to you soon.</div>
    <?php endif; ?>

    <form method="post">
      <div class="field">
        <label>Your Name</label>
        <input type="text" name="name" required>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <div class="field">
        <label>Message</label>
        <textarea name="message" required placeholder="How can we help?" rows="5"></textarea>
      </div>
      <button type="submit" class="btn btn-accent btn-block">Send Message</button>
    </form>

    <p class="form-note">Or reach us directly at hello@noirthreads.lk / +94 77 123 4567</p>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
