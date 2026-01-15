<?php
include("../config/db.php");

$success = "";
$error = "";

/* ===== Allowed Bangladesh Divisions ===== */
$allowed_divisions = [
    "Dhaka",
    "Chittagong",
    "Rajshahi",
    "Khulna",
    "Barishal",
    "Rangpur",
    "Mymensingh",
    "Sylhet"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $lab_name = trim($_POST['lab_name'] ?? '');
    $address = $_POST['address'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    /* ===== VALIDATION ===== */

    if ($lab_name === '' || $address === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } elseif (!in_array($address, $allowed_divisions)) {
        $error = "Please select a valid division.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format. Email must contain '@'.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8}$/', $password)) {
        $error = "Password must be exactly 8 characters and include 1 uppercase, 1 lowercase, and 1 number.";
    } else {

        /* ===== CHECK DUPLICATE EMAIL ===== */
        $check = $conn->prepare("SELECT lab_id FROM lab WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "This email is already registered.";
        } else {

            /* ===== INSERT LAB ===== */
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO lab (lab_name, address, email, password_hash, status)
                 VALUES (?, ?, ?, ?, 'inactive')"
            );
            $stmt->bind_param("ssss", $lab_name, $address, $email, $password_hash);

            if ($stmt->execute()) {
                $success = "Registration successful. Please wait for admin approval.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lab Registration | HLA Finder</title>
</head>

<body>

    <h2>Laboratory Registration</h2>

    <?php
    if ($error) {
        echo "<p style='color:red;'>$error</p>";
    }
    if ($success) {
        echo "<p style='color:green;'>$success</p>";
    }
    ?>

    <form method="POST">

        <label>Lab Name *</label><br>
        <input type="text" name="lab_name" required>
        <br><br>

        <label>Division *</label><br>
        <select name="address" required>
            <option value="">-- Select Division --</option>
            <option value="Dhaka">Dhaka</option>
            <option value="Chittagong">Chittagong</option>
            <option value="Rajshahi">Rajshahi</option>
            <option value="Khulna">Khulna</option>
            <option value="Barishal">Barishal</option>
            <option value="Rangpur">Rangpur</option>
            <option value="Mymensingh">Mymensingh</option>
            <option value="Sylhet">Sylhet</option>
        </select>
        <br><br>

        <label>Email *</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Password *</label><br>
        <input type="password" name="password" minlength="8" maxlength="8" required>
        <br><br>

        <button type="submit">Register</button>

    </form>

    <p>
        Already registered?
        <a href="lab_login.php">Login here</a>
    </p>

</body>

</html>