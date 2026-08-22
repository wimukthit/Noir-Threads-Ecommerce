<?php
require_once 'config/db.php';

$redirect = $_POST['redirect'] ?? 'cart.php';
$redirect = in_array($redirect, ['cart.php', 'checkout.php']) ? $redirect : 'cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['coupon_code'])) {
    $code = strtoupper(trim($_POST['coupon_code']));

    $stmt = $conn->prepare("SELECT * FROM coupons WHERE code = ? AND active = 1 AND (expiry_date IS NULL OR expiry_date >= CURDATE())");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $coupon = $stmt->get_result()->fetch_assoc();

    if ($coupon) {
        $_SESSION['coupon'] = [
            'code' => $coupon['code'],
            'type' => $coupon['discount_type'],
            'value' => $coupon['discount_value'],
        ];
        unset($_SESSION['coupon_error']);
    } else {
        $_SESSION['coupon_error'] = "Invalid or expired coupon code.";
        unset($_SESSION['coupon']);
    }
}

header("Location: " . $redirect);
exit;
