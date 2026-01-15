<?php
include("../config/db.php");
include("../includes/header.php");

$error = "";
$success = "";

/* ======================
   PASSWORD RULE
   - Max 8 characters
   - Must contain letters & numbers
   ====================== */
function isValidPassword($password)
{
    return strlen($password) <= 8 &&
        preg_match('/[A-Za-z]/', $password) &&
        preg_match('/[0-9]/', $password);
}

/* ======================
   NAME RULE
   - Only letters & spaces
   - Max 20 characters
   ====================== */
function isValidName($name)
{
    return strlen($name) <= 20 &&
        preg_match('/^[A-Za-z ]+$/', $name);
}

/* ======================
   PHONE RULE
   - Exactly 14 characters
   - Must start with +880
   - Only digits after +880
   ====================== */
function isValidPhone($phone)
{
    return preg_match('/^\+880[0-9]{10}$/', $phone);
}

/* ======================
   EMAIL RULE
   - Exactly one @
   - At least one . after @
   - At least one letter before & after @
   ====================== */
function isValidEmailCustom($email)
{
    return preg_match(
        '/^[A-Za-z]+[A-Za-z0-9._%+-]*@[A-Za-z]+[A-Za-z0-9.-]*\.[A-Za-z]{2,}$/',
        $email
    );
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $dob = $_POST['dob'];
    $sex = $_POST['sex'];
    $phone = trim($_POST['phone']);
    $division = $_POST['division'];

    /* ===== VALIDATION ===== */
    if (!isValidName($name)) {
        $error = "Full Name must contain only letters and max 20 characters.";
    } elseif (!isValidEmailCustom($email)) {
        $error = "Email must contain one '@', a '.' after '@', and letters before and after '@'.";
    } elseif (!isValidPhone($phone)) {
        $error = "Phone number must be 14 characters, start with +880, and contain only digits.";
    } elseif (!in_array($role, ['donor', 'receiver'])) {
        $error = "Invalid role selected.";
    } elseif (!isValidPassword($password)) {
        $error = "Password must be max 8 characters and contain letters and numbers.";
    } elseif (empty($dob) || empty($sex) || empty($division)) {
        $error = "All fields are required.";
    } else {

        /* ===== CHECK DUPLICATE EMAIL ===== */
        $check = $conn->prepare(
            "SELECT user_id FROM users WHERE email = ?"
        );
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users
                (role, name, dob, sex, phone_no, address_by_divisions, email, password_hash, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')"
            );

            $stmt->bind_param(
                "ssssssss",
                $role,
                $name,
                $dob,
                $sex,
                $phone,
                $division,
                $email,
                $hash
            );

            if ($stmt->execute()) {
                $success = "Registration successful. You may now log in.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<div class="register-wrapper">

    <h2>User Registration</h2>

    <?php
    if ($error)
        echo "<p style='color:red;'>$error</p>";
    if ($success)
        echo "<p style='color:green;'>$success</p>";
    ?>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" maxlength="20" required>

        <input type="date" name="dob" required>

        <select name="sex" required>
            <option value="">Select Sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Others">Others</option>
        </select>

        <input type="text" name="phone" placeholder="Phone (+880XXXXXXXXXX)" required>

        <select name="division" required>
            <option value="">Select Division</option>
            <option value="Dhaka">Dhaka</option>
            <option value="Chittagong">Chittagong</option>
            <option value="Khulna">Khulna</option>
            <option value="Rajshahi">Rajshahi</option>
            <option value="Barisal">Barisal</option>
            <option value="Sylhet">Sylhet</option>
            <option value="Rangpur">Rangpur</option>
            <option value="Mymensingh">Mymensingh</option>
        </select>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password (max 8 chars)" maxlength="8" required>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="donor">Donor</option>
            <option value="receiver">Receiver</option>
        </select>

        <button type="submit">Register</button>
    </form>

    <div class="register-links">
        <p>Already registered? <a href="login.php">Login here</a></p>
    </div>

</div>

<?php include "../includes/footer.php"; ?>