<?php
require_once 'config/db.php';

$redirect = $_GET['redirect'] ?? 'cart.php';
$redirect = in_array($redirect, ['cart.php', 'checkout.php']) ? $redirect : 'cart.php';

unset($_SESSION['coupon'], $_SESSION['coupon_error']);

header("Location: " . $redirect);
exit;
