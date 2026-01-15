<?php
session_start();
include("../config/db.php");

/* ===== PROTECT LAB ===== */
if (!isset($_SESSION['lab_id'])) {
    header("Location: ../lab/lab_login.php");
    exit;
}

/* ===== FETCH TRANSPLANT HISTORY ===== */
$sql = "
    SELECT 
        t.transplant_id,
        t.transplant_date,
        t.organ_type,
        t.outcome,
        d.name AS donor_name,
        r.name AS receiver_name
    FROM transplant_info t
    JOIN users d ON t.donor_id = d.user_id
    JOIN users r ON t.receiver_id = r.user_id
    ORDER BY t.transplant_date DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Transplant History</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f7fb;
        }

        .container {
            width: 950px;
            margin: 50px auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-top: 0;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
            text-align: center;
        }

        th {
            background: #3498db;
            color: #ffffff;
        }

        tr:hover {
            background: #f8f9fa;
        }

        /* ===== OUTCOME COLORS ===== */
        .outcome-successful {
            color: #27ae60;
            font-weight: bold;
        }

        .outcome-rejected {
            color: #c0392b;
            font-weight: bold;
        }

        .outcome-ongoing {
            color: #f39c12;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #3498db;
            font-weight: bold;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Transplant History</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Receiver</th>
                <th>Donor</th>
                <th>Organ</th>
                <th>Transplant Date</th>
                <th>Outcome</th>
            </tr>

            <?php if ($result && $result->num_rows > 0) { ?>

                <?php while ($row = $result->fetch_assoc()) { ?>

                    <?php
                    $outcomeClass =
                        strtolower($row['outcome']) === 'successful' ? 'outcome-successful' :
                        (strtolower($row['outcome']) === 'rejected' ? 'outcome-rejected' : 'outcome-ongoing');
                    ?>

                    <tr>
                        <td><?php echo $row['transplant_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['receiver_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['organ_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['transplant_date']); ?></td>
                        <td class="<?php echo $outcomeClass; ?>">
                            <?php echo htmlspecialchars($row['outcome']); ?>
                        </td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="6">No transplant records found.</td>
                </tr>

            <?php } ?>

        </table>

        <a href="../lab/dashboard.php" class="back-link">← Back to Lab Dashboard</a>

    </div>

</body>

</html>