<?php
include("../config/db.php");
include("../includes/header.php");

if (!isset($_GET['email'])) {
    die("Invalid request.");
}

$email = $_GET['email'];
$error = "";

// Password rule
function isValidPassword($password)
{
    return strlen($password) <= 8 &&
        preg_match('/[A-Za-z]/', $password) &&
        preg_match('/[0-9]/', $password);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (!isValidPassword($new)) {
        $error = "Password must be max 8 characters and contain letters and numbers.";
    } else {

        $hash = password_hash($new, PASSWORD_DEFAULT);

        mysqli_query(
            $conn,
            "UPDATE users SET password_hash='$hash' WHERE email='$email'"
        );

        header("Location: login.php");
        exit;
    }
}
?>

<div class="container">
    <h2>Reset Password</h2>

    <?php if ($error)
        echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="password" name="new_password" placeholder="New Password" maxlength="8" required>

        <input type="password" name="confirm_password" placeholder="Confirm Password" maxlength="8" required>

        <button type="submit">Reset Password</button>
    </form>
</div>

<?php include "../includes/footer.php"; ?>