<?php
// ==============================================
// Database connection settings
// Change these if your MySQL setup is different
// ==============================================
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "tshirt_store";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session on every page that includes this file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
