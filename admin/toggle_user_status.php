<?php
include("../includes/auth.php");
requireRole(['admin']);

include("../config/db.php");

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $conn->query("
        UPDATE users
        SET status = IF(status='active','inactive','active')
        WHERE user_id = $id AND role='donor'
    ");
}

header("Location: manage_donors.php");
exit;
