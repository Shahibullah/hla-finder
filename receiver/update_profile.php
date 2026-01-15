<?php
session_start();
include("../config/db.php");

/* ===== PROTECT RECEIVER ===== */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receiver') {
    header("Location: ../public/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

/* ======================
   PHONE VALIDATION
   ====================== */
function isValidPhone($phone)
{
    return preg_match('/^\+880[0-9]{10}$/', $phone);
}

/* ======================
   NAME VALIDATION
   ====================== */
function isValidName($name)
{
    return strlen($name) <= 20 && preg_match('/^[A-Za-z ]+$/', $name);
}

/* ===== FETCH CURRENT DATA ===== */
$stmt = $conn->prepare(
    "SELECT name, phone_no, address_by_divisions
     FROM users WHERE user_id = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

/* ===== UPDATE PROFILE ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $division = $_POST['division'];

    if (!isValidName($name)) {
        $error = "Name must contain only letters and max 20 characters.";
    } elseif (!isValidPhone($phone)) {
        $error = "Phone must be 14 characters and start with +880.";
    } elseif (empty($division)) {
        $error = "Division is required.";
    } else {

        $stmt = $conn->prepare(
            "UPDATE users
             SET name = ?, phone_no = ?, address_by_divisions = ?
             WHERE user_id = ?"
        );
        $stmt->bind_param("sssi", $name, $phone, $division, $user_id);

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
            $user['name'] = $name;
            $user['phone_no'] = $phone;
            $user['address_by_divisions'] = $division;
        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Personal Information</title>

    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #eef2f7, #f9fbfd);
        }

        /* ===== PAGE TITLE ===== */
        .page-title {
            margin: 40px 60px;
            font-size: 26px;
            font-weight: bold;
            color: #2c3e50;
        }

        /* ===== CARD ===== */
        .profile-card {
            width: 420px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* ===== INPUTS ===== */
        input,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #3498db;
        }

        /* ===== BUTTON ===== */
        button {
            width: 100%;
            padding: 12px;
            background: #27ae60;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1e8449;
        }

        /* ===== MESSAGES ===== */
        .error {
            color: #c0392b;
            text-align: center;
            margin-bottom: 15px;
        }

        .success {
            color: #27ae60;
            text-align: center;
            margin-bottom: 15px;
        }

        /* ===== BACK LINK ===== */
        .back-link {
            display: block;
            margin: 30px 60px;
            color: #6a0dad;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="page-title">Update Personal Information</div>

    <div class="profile-card">

        <?php
        if ($error)
            echo "<div class='error'>$error</div>";
        if ($success)
            echo "<div class='success'>$success</div>";
        ?>

        <form method="POST">

            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                placeholder="Full Name" required>

            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone_no']); ?>"
                placeholder="Phone (+880XXXXXXXXXX)" required>

            <select name="division" required>
                <option value="">Select Division</option>
                <?php
                $divisions = [
                    "Dhaka",
                    "Chittagong",
                    "Khulna",
                    "Rajshahi",
                    "Barisal",
                    "Sylhet",
                    "Rangpur",
                    "Mymensingh"
                ];
                foreach ($divisions as $d) {
                    $selected = ($user['address_by_divisions'] === $d) ? "selected" : "";
                    echo "<option value='$d' $selected>$d</option>";
                }
                ?>
            </select>

            <button type="submit">Update Profile</button>

        </form>

    </div>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

</body>

</html>