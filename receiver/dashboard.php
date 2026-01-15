<?php
session_start();
include("../config/db.php");

/* ===== PROTECT RECEIVER ===== */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receiver') {
    header("Location: ../public/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ===== GET RECEIVER INFO ===== */
$stmt = $conn->prepare(
    "SELECT name, email, phone_no, address_by_divisions, HLA_Type
     FROM users
     WHERE user_id = ? AND role = 'receiver'"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$receiver = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Receiver Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #eef2f7, #f9fbfd);
        }

        /* ===== CARD ===== */
        .dashboard-card {
            width: 520px;
            margin: 60px auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* ===== HEADER ===== */
        .dashboard-card h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        /* ===== INFO ROWS ===== */
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ecf0f1;
            font-size: 15px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .label {
            color: #7f8c8d;
            font-weight: 600;
        }

        .value {
            color: #2c3e50;
            font-weight: 500;
        }

        /* ===== HLA HIGHLIGHT ===== */
        .hla-box {
            background: #f4f9ff;
            padding: 12px;
            border-left: 5px solid #3498db;
            margin-top: 15px;
            border-radius: 6px;
            font-weight: bold;
        }

        /* ===== ACTIONS ===== */
        .actions {
            margin-top: 30px;
        }

        .actions a {
            display: block;
            text-align: center;
            padding: 12px;
            margin-bottom: 12px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.2s ease;
        }

        .actions a:hover {
            background: #2980b9;
        }

        .actions .secondary {
            background: #95a5a6;
        }

        .actions .secondary:hover {
            background: #7f8c8d;
        }

        /* ===== FOOTER ===== */
        .footer-note {
            text-align: center;
            margin-top: 20px;
            color: #95a5a6;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="dashboard-card">

        <h2>Receiver Dashboard</h2>

        <div class="info-row">
            <span class="label">Name</span>
            <span class="value"><?php echo htmlspecialchars($receiver['name']); ?></span>
        </div>

        <div class="info-row">
            <span class="label">Email</span>
            <span class="value"><?php echo htmlspecialchars($receiver['email']); ?></span>
        </div>

        <div class="info-row">
            <span class="label">Phone</span>
            <span class="value"><?php echo htmlspecialchars($receiver['phone_no']); ?></span>
        </div>

        <div class="info-row">
            <span class="label">Address</span>
            <span class="value"><?php echo htmlspecialchars($receiver['address_by_divisions']); ?></span>
        </div>

        <div class="hla-box">
            HLA Type:
            <?php
            echo $receiver['HLA_Type']
                ? htmlspecialchars($receiver['HLA_Type'])
                : 'Not set yet';
            ?>
        </div>

        <div class="actions">
            <a href="update_profile.php">Update Personal Information</a>
            <a href="view_match_status.php">View Match Status</a>
            <a href="../public/logout.php" class="secondary">Logout</a>
        </div>

        <div class="footer-note">
            Secure access • HLA Finder System
        </div>

    </div>

</body>

</html>