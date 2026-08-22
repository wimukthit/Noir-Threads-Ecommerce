<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['cart'])) {
    foreach ($_POST['quantity'] as $key => $qty) {
        $qty = max(1, intval($qty));
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] = $qty;
        }
    }
}

header("Location: cart.php");
exit;

