<?php
include("../config/db.php");

$lab_id = (int) $_GET['id'];
$currentStatus = $_GET['status'];

$newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';

mysqli_query(
    $conn,
    "UPDATE lab SET status='$newStatus' WHERE lab_id=$lab_id"
);

header("Location: manage_labs.php");
exit;
