<?php
// Auth guard only — no HTML output here, so it's safe to include
// before any header()/redirect logic that a page still needs to run.
if (!isset($_SESSION['admin_id'])) {
    header("Location: /tshirt-store/admin/login.php");
    exit;
}
