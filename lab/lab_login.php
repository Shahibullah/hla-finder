<?php
session_start();
include("../config/db.php");

/* ===== CSRF TOKEN SETUP ===== */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ===== CSRF VALIDATION ===== */
    if (
        !isset($_POST['csrf_token']) ||
        $_POST['csrf_token'] !== $_SESSION['csrf_token']
    ) {
        die("Invalid CSRF token.");
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }

    // Password validation (8 chars, 1 upper, 1 lower, 1 number)
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8}$/', $password)) {
        $error = "Password must be exactly 8 characters with uppercase, lowercase and number.";
    } else {

        // Login (NO status check)
        $stmt = $conn->prepare(
            "SELECT lab_id, lab_name, password_hash, status
             FROM lab WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $lab = $result->fetch_assoc();

            if (password_verify($password, $lab['password_hash'])) {

                $_SESSION['lab_id'] = $lab['lab_id'];
                $_SESSION['lab_name'] = $lab['lab_name'];
                $_SESSION['lab_status'] = $lab['status'];

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit;

            } else {
                $error = "Incorrect password.";
            }

        } else {
            $error = "Lab account not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lab Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        .login-container {
            width: 380px;
            margin: 80px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .links {
            text-align: center;
            margin-top: 15px;
        }

        .links a {
            text-decoration: none;
            color: #3498db;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .home-link {
            text-align: center;
            margin-top: 20px;
        }

        .home-link a {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            text-decoration: none;
        }

        .home-link a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>

    <body>

        <div class="login-container">

            <h2>Lab Login</h2>

            <?php
            if ($error) {
                echo "<p class='error'>$error</p>";
            }
            ?>

            <form method="POST">

                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label>Email</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" minlength="8" maxlength="8" required>

                <button type="submit">Login</button>

            </form>

            <div class="links">
                <p><a href="lab_forget_password.php">Forgot Password?</a></p>
                <p>New Lab? <a href="lab_register.php">Register Here</a></p>
            </div>

            <div class="home-link">
                <a href="../public/index.php">Home</a>
            </div>

        </div>

    </body>


</html>