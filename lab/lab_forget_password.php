<?php
include("../config/db.php");

$error = "";
$success = "";

/* ======================
   PASSWORD VALIDATION
   ====================== */
function isValidPassword($password)
{
    return strlen($password) <= 8 &&
        preg_match('/[A-Za-z]/', $password) &&
        preg_match('/[0-9]/', $password);
}

/* ======================
   EMAIL VALIDATION
   ====================== */
function isValidEmailCustom($email)
{
    return preg_match(
        '/^[A-Za-z]+[A-Za-z0-9._%+-]*@[A-Za-z]+[A-Za-z0-9.-]*\.[A-Za-z]{2,}$/',
        $email
    );
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];

    if (!isValidEmailCustom($email)) {
        $error = "Invalid email format.";
    } elseif (!isValidPassword($new_password)) {
        $error = "Password must be max 8 characters and contain letters and numbers.";
    } else {

        /* ===== CHECK LAB EMAIL ===== */
        $stmt = $conn->prepare(
            "SELECT lab_id FROM lab WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows !== 1) {
            $error = "No lab account found with this email.";
        } else {

            $hash = password_hash($new_password, PASSWORD_DEFAULT);

            /* ===== UPDATE PASSWORD ===== */
            $stmt = $conn->prepare(
                "UPDATE lab SET password_hash = ? WHERE email = ?"
            );
            $stmt->bind_param("ss", $hash, $email);

            if ($stmt->execute()) {
                $success = "Password reset successful. You may now log in.";
            } else {
                $error = "Failed to reset password. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lab Forgot Password</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
        }

        .container {
            width: 420px;
            margin: 60px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 12px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background: #2980b9;
        }

        .error {
            color: red;
            text-align: center;
        }

        .success {
            color: green;
            text-align: center;
        }

        .links {
            text-align: center;
            margin-top: 15px;
        }

        .links a {
            color: #3498db;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Reset Lab Password</h2>

        <?php
        if ($error)
            echo "<p class='error'>$error</p>";
        if ($success)
            echo "<p class='success'>$success</p>";
        ?>

        <form method="POST">

            <input type="email" name="email" placeholder="Registered Lab Email" required>

            <input type="password" name="new_password" placeholder="New Password (max 8 chars)" maxlength="8" required>

            <button type="submit">Reset Password</button>
        </form>

        <div class="links">
            <p><a href="lab_login.php">Back to Lab Login</a></p>
        </div>

    </div>

</body>

</html>