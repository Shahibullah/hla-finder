<?php
include("../config/db.php");
include("../includes/auth.php");

/* ===== ADMIN ONLY ===== */
requireRole(['admin']);

/* ===== ACTIVATE DONOR ===== */
if (isset($_GET['activate'])) {
    $id = (int) $_GET['activate'];
    $stmt = $conn->prepare(
        "UPDATE users SET status='active'
         WHERE user_id=? AND role='donor'"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_donors.php");
    exit;
}

/* ===== DEACTIVATE DONOR ===== */
if (isset($_GET['deactivate'])) {
    $id = (int) $_GET['deactivate'];
    $stmt = $conn->prepare(
        "UPDATE users SET status='inactive'
         WHERE user_id=? AND role='donor'"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_donors.php");
    exit;
}

/* ===== DELETE DONOR (SAFE DELETE) ===== */
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Check foreign key usage
    $check = $conn->prepare(
        "SELECT COUNT(*) FROM transplant_info
         WHERE donor_id = ? OR receiver_id = ?"
    );
    $check->bind_param("ii", $id, $id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        // Cannot delete
        header("Location: manage_donors.php?error=linked");
        exit;
    }

    // Safe to delete
    $stmt = $conn->prepare(
        "DELETE FROM users WHERE user_id=? AND role='donor'"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: manage_donors.php?deleted=1");
    exit;
}

/* ===== FILTER BY DIVISION ===== */
$division = $_GET['division'] ?? 'All';
$query = "SELECT * FROM users WHERE role='donor'";
$params = [];

if ($division !== 'All') {
    $query .= " AND address_by_divisions=?";
    $params[] = $division;
}

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param("s", ...$params);
}
$stmt->execute();
$donors = $stmt->get_result();

/* ===== COUNTS ===== */
$activeCount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS c FROM users
         WHERE role='donor' AND status='active'"
    )
)['c'];

$inactiveCount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS c FROM users
         WHERE role='donor' AND status='inactive'"
    )
)['c'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Donors</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background: #f1f4f9;
            margin: 0;
        }

        .container {
            width: 95%;
            margin: 30px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-top: 0;
            color: #2c3e50;
        }

        /* ===== SUMMARY CARDS ===== */
        .cards {
            display: flex;
            gap: 20px;
            margin: 25px 0;
        }

        .card {
            flex: 1;
            padding: 30px;
            border-radius: 12px;
            color: #fff;
            text-align: center;
        }

        .card h3 {
            margin: 0;
            font-size: 20px;
        }

        .card span {
            display: block;
            font-size: 36px;
            font-weight: bold;
            margin-top: 10px;
        }

        .green {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .red {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        /* ===== FILTER ===== */
        .filter {
            margin: 20px 0;
        }

        .filter select,
        .filter button {
            padding: 7px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .filter button {
            background: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }

        .filter button:hover {
            background: #2980b9;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #3498db;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        tr:hover {
            background: #f9fbfd;
        }

        /* ===== STATUS BADGES ===== */
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: bold;
        }

        .badge-active {
            background: #eafaf1;
            color: #27ae60;
        }

        .badge-inactive {
            background: #fdecea;
            color: #c0392b;
        }

        /* ===== ACTION LINKS ===== */
        .actions a {
            margin-right: 10px;
            font-weight: bold;
            text-decoration: none;
        }

        .activate {
            color: #27ae60;
        }

        .deactivate {
            color: #f39c12;
        }

        .delete {
            color: #e74c3c;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        /* ===== MESSAGES ===== */
        .msg-error {
            color: #c0392b;
            margin: 10px 0;
        }

        .msg-success {
            color: #27ae60;
            margin: 10px 0;
        }

        /* ===== FOOTER LINK ===== */
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Manage Donors</h2>

        <?php if (isset($_GET['error'])) { ?>
            <p class="msg">
                ❌ Cannot delete donor. This donor is linked to transplant records.
            </p>
        <?php } ?>

        <?php if (isset($_GET['deleted'])) { ?>
            <p class="msg" style="color:green;">
                ✅ Donor deleted successfully.
            </p>
        <?php } ?>

        <div class="cards">
            <div class="card green">
                Active Donors<br><strong><?php echo $activeCount; ?></strong>
            </div>
            <div class="card red">
                Inactive Donors<br><strong><?php echo $inactiveCount; ?></strong>
            </div>
        </div>

        <form method="GET" style="margin-top:20px;">
            <label><strong>Select Division:</strong></label>
            <select name="division">
                <option>All</option>
                <?php
                $divs = ['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh', 'Sylhet'];
                foreach ($divs as $d) {
                    $sel = ($division == $d) ? 'selected' : '';
                    echo "<option $sel>$d</option>";
                }
                ?>
            </select>
            <button type="submit">Filter</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Division</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = $donors->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['address_by_divisions']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php if ($row['status'] === 'active') { ?>
                            <a class="deactivate" href="manage_donors.php?deactivate=<?php echo $row['user_id']; ?>">
                                Deactivate
                            </a>
                        <?php } else { ?>
                            <a class="activate" href="manage_donors.php?activate=<?php echo $row['user_id']; ?>">
                                Activate
                            </a>
                        <?php } ?>
                        |
                        <a class="delete" href="manage_donors.php?delete=<?php echo $row['user_id']; ?>"
                            onclick="return confirm('Delete donor permanently? This cannot be undone.');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>

        </table>

        <br>
        <a href="dashboard.php">← Back to Admin Dashboard</a>

    </div>

</body>

</html>