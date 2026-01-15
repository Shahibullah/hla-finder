<?php
include("../config/db.php");
include("../includes/header.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {

        $check = mysqli_query(
            $conn,
            "SELECT user_id FROM users WHERE email='$email' AND status='active'"
        );

        if (mysqli_num_rows($check) === 0) {
            $error = "No active account found with this email.";
        } else {
            header("Location: reset_password.php?email=$email");
            exit;
        }
    }
}
?>

<div class="container">
    <h2>Forgot Password</h2>

    <?php if ($error)
        echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter your registered email" required>

        <button type="submit">Continue</button>
    </form>

    <a href="login.php">Back to Login</a>
</div>

<?php include "../includes/footer.php"; ?>