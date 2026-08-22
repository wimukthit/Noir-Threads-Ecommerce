<?php
require_once 'config/db.php';
unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
header("Location: index.php");
exit;
