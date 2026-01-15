<?php
// Allow all logged-in roles
include("../includes/auth.php");
requireRole(['admin', 'donor', 'receiver']);

include("../includes/header.php");
?>

<h2>Password Changed Successfully ✅</h2>

<p>Your password has been updated securely.</p>

<ul>
    <li><a href="index.php">Go to Home</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

<?php include "../includes/footer.php"; ?>