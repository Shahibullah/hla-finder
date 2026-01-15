<?php
session_start();
include("../config/db.php");

/* ===== PROTECT LAB ===== */
if (!isset($_SESSION['lab_id'])) {
    header("Location: lab_login.php");
    exit;
}

$lab_id = $_SESSION['lab_id'];

/* ===== FETCH LAB INFO ===== */
$stmt = $conn->prepare(
    "SELECT lab_name, address, email, status
     FROM lab
     WHERE lab_id = ?"
);
$stmt->bind_param("i", $lab_id);
$stmt->execute();
$result = $stmt->get_result();
$lab = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lab Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #eef2f7, #f9fbfd);
        }

        /* ===== CARD ===== */
        .dashboard-card {
            width: 650px;
            margin: 50px auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-top: 0;
            text-align: center;
            color: #2c3e50;
        }

        /* ===== INFO TABLE ===== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .info-table td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }

        .info-table td:first-child {
            font-weight: bold;
            color: #7f8c8d;
        }

        .status-active {
            color: #27ae60;
            font-weight: bold;
        }

        .status-inactive {
            color: #c0392b;
            font-weight: bold;
        }

        /* ===== ACTIONS ===== */
        .actions {
            margin-top: 30px;
        }

        .actions h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .action-links {
            list-style: none;
            padding: 0;
        }

        .action-links li {
            margin-bottom: 12px;
        }

        .action-links a {
            display: inline-block;
            padding: 12px 18px;
            background: #3498db;
            color: #ffffff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .action-links a:hover {
            background: #2980b9;
        }

        /* ===== WARNING ===== */
        .warning {
            background: #fdecea;
            color: #c0392b;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
        }

        /* ===== FOOTER ===== */
        .footer-links {
            margin-top: 30px;
            text-align: center;
        }

        .footer-links a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="dashboard-card">

        <h2>Lab Dashboard</h2>

        <table class="info-table">
            <tr>
                <td>Lab Name</td>
                <td><?php echo htmlspecialchars($lab['lab_name']); ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?php echo htmlspecialchars($lab['address']); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo htmlspecialchars($lab['email']); ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td class="<?php echo $lab['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo ucfirst($lab['status']); ?>
                </td>
            </tr>
        </table>

        <div class="actions">
            <h3>Lab Actions</h3>

            <?php if ($lab['status'] === 'active') { ?>

                <ul class="action-links">
                    <li>
                        <a href="set_hla_type.php">
                            Set Donor / Receiver HLA Type
                        </a>
                    </li>

                    <li>
                        <a href="../transplant/transplant_action.php">
                            Transplantation Action
                        </a>
                    </li>

                    <li>
                        <a href="../transplant/transplant_history.php">
                            View Transplant History
                        </a>
                    </li>
                </ul>

            <?php } else { ?>

                <div class="warning">
                    Your lab is currently inactive.
                    Lab actions are disabled.
                </div>

            <?php } ?>
        </div>

        <div class="footer-links">
            <a href="../public/logout.php">Logout</a>
        </div>

    </div>

</body>

</html>