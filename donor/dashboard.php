<?php
include("../config/db.php");
include("../includes/auth.php");

/* ===== DONOR ONLY ===== */
requireRole(['donor']);

/* ===== FETCH DONOR DATA ===== */
$stmt = $conn->prepare(
    "SELECT name, HLA_Type, HLA_Class
     FROM users
     WHERE user_id = ? AND role = 'donor'"
);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    // Safety fallback
    echo "User not found.";
    exit;
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Donor Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 800px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
        }

        h2 {
            color: #2c3e50;
        }

        .section {
            margin-top: 25px;
        }

        .section h3 {
            color: #34495e;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }

        .link-list a {
            display: block;
            margin: 6px 0;
            color: #3498db;
            text-decoration: none;
        }

        .link-list a:hover {
            text-decoration: underline;
        }

        .hla-box {
            background: #f0f3f7;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .notice {
            color: #e74c3c;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
        <p>You are logged in as a donor.</p>

        <!-- ===== HLA SECTION ===== -->
        <div class="section">
            <h3>Your HLA Typing Information</h3>

            <?php if (!empty($user['HLA_Type']) && !empty($user['HLA_Class'])) { ?>

                <div class="hla-box">
                    <p><strong>HLA Type:</strong>
                        <?php echo htmlspecialchars($user['HLA_Type']); ?>
                    </p>
                    <p><strong>HLA Class:</strong>
                        <?php echo htmlspecialchars($user['HLA_Class']); ?>
                    </p>
                </div>

            <?php } else { ?>

                <p class="notice">No HLA typing data available.</p>

            <?php } ?>
        </div>

        <!-- ===== DONOR ACTIONS ===== -->
        <div class="section">
            <h3>Donor Actions</h3>
            <div class="link-list">
                <a href="view_donation_status.php">View Donation Status</a>
                <a href="update_profile.php">Update Personal Information</a>
            </div>
        </div>

        <!-- ===== ACCOUNT ===== -->
        <div class="section">
            <h3>Account</h3>
            <div class="link-list">
                <a href="../public/change_password.php">Change Password</a>
                <a href="../public/index.php">Home</a>
                <a href="../public/logout.php">Logout</a>
            </div>
        </div>

        <p style="margin-top:30px;">
            Thank you for being a donor and helping save lives.
        </p>

    </div>

</body>

</html>