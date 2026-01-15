<?php
include("../config/db.php");

$lab_id = (int) $_GET['id'];

mysqli_query($conn, "DELETE FROM lab WHERE lab_id=$lab_id");

header("Location: manage_labs.php");
exit;
