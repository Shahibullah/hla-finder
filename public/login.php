<?php
session_start();
include("../config/db.php");
include("../includes/header.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {

        /* ======================
           1️⃣ CHECK USERS TABLE
           ====================== */
        $stmt = $conn->prepare(
            "SELECT user_id, role, password_hash, status
             FROM users
             WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if ($user['status'] !== 'active') {
                $error = "Your account is inactive. Please contact admin.";
            } elseif (password_verify($password, $user['password_hash'])) {

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];

                // ROLE BASED REDIRECT
                if ($user['role'] === 'admin') {
                    header("Location: /hla_system/admin/dashboard.php");
                } elseif ($user['role'] === 'donor') {
                    header("Location: /hla_system/donor/dashboard.php");
                } elseif ($user['role'] === 'receiver') {
                    header("Location: /hla_system/receiver/dashboard.php");
                }
                exit;
            } else {
                $error = "Incorrect password.";
            }

        } else {

            /* ======================
               2️⃣ CHECK LAB TABLE
               ====================== */
            $labStmt = $conn->prepare(
                "SELECT lab_id, password_hash, status
                 FROM lab
                 WHERE email = ?"
            );
            $labStmt->bind_param("s", $email);
            $labStmt->execute();
            $labResult = $labStmt->get_result();

            if ($labResult->num_rows === 1) {

                $lab = $labResult->fetch_assoc();

                if ($lab['status'] !== 'active') {
                    $error = "Lab account is inactive.";
                } elseif (password_verify($password, $lab['password_hash'])) {

                    $_SESSION['lab_id'] = $lab['lab_id'];
                    $_SESSION['role'] = 'lab';

                    header("Location: /hla_system/lab/dashboard.php");
                    exit;
                } else {
                    $error = "Incorrect password.";
                }

            } else {
                $error = "Email not found.";
            }
        }
    }
}
?>

<div class="login-wrapper">

    <h2>Login</h2>

    <?php if (!empty($error)) { ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <!-- 🔑 EXTRA LINKS -->
    <div class="login-links">
        <a href="register.php">Create an account</a>
        <a href="forgot_password.php">Forgot Password?</a>
        <a href="index.php">⬅ Back to Home</a>
    </div>

</div>

<?php include "../includes/footer.php"; ?>