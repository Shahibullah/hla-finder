<?php
// Allow all logged-in roles
include("../includes/auth.php");
requireRole(['admin', 'donor', 'receiver']);

include("../config/db.php");
include("../includes/header.php");

// Password rule: max 8 chars, must contain letters & numbers
function isValidPassword($password)
{
    return strlen($password) <= 8 &&
        preg_match('/[A-Za-z]/', $password) &&
        preg_match('/[0-9]/', $password);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {
        $error = "New password and confirm password do not match.";
    } elseif (!isValidPassword($new)) {
        $error = "Password must be max 8 characters and contain alphabets and numbers.";
    } else {

        $user_id = $_SESSION['user_id'];

        // Get current password hash
        $sql = "SELECT password_hash FROM users WHERE user_id='$user_id'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);

        if (!$user || !password_verify($current, $user['password_hash'])) {
            $error = "Current password is incorrect.";
        } else {

            $new_hash = password_hash($new, PASSWORD_DEFAULT);

            mysqli_query(
                $conn,
                "UPDATE users SET password_hash='$new_hash' WHERE user_id='$user_id'"
            );

            // Redirect to confirmation page
            header("Location: password_changed.php");
            exit;
        }
    }
}
?>

<h2>Change Password</h2>

<?php if ($error)
    echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">

    <input type="password" id="current_password" name="current_password" placeholder="Current Password" maxlength="8"
        required><br><br>

    <input type="password" id="new_password" name="new_password" placeholder="New Password" maxlength="8"
        required><br><br>

    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password"
        maxlength="8" required><br><br>

    <input type="checkbox" onclick="togglePassword()"> Show Password<br><br>

    <button type="submit">Change Password</button>
</form>

<a href="index.php">Back</a>

<script>
    function togglePassword() {
        let fields = [
            document.getElementById("current_password"),
            document.getElementById("new_password"),
            document.getElementById("confirm_password")
        ];

        fields.forEach(function (field) {
            field.type = field.type === "password" ? "text" : "password";
        });
    }
</script>

<?php include "../includes/footer.php"; ?>