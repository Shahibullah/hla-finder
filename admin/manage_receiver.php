<?php
include("../config/db.php");
include("../includes/auth.php");

/* ===== ADMIN ONLY ===== */
requireRole(['admin']);

$message = "";

/* ===== ACTIVATE RECEIVER ===== */
if (isset($_GET['activate'])) {
    $id = (int) $_GET['activate'];
    $stmt = $conn->prepare(
        "UPDATE users SET status='active'
         WHERE user_id=? AND role='receiver'"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_receiver.php");
    exit;
}

/* ===== DEACTIVATE RECEIVER ===== */
if (isset($_GET['deactivate'])) {
    $id = (int) $_GET['deactivate'];
    $stmt = $conn->prepare(
        "UPDATE users SET status='inactive'
         WHERE user_id=? AND role='receiver'"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_receiver.php");
    exit;
}

/* ===== FORCE DELETE RECEIVER ===== */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    // START TRANSACTION
    $conn->begin_transaction();

    try {
        // 1️⃣ Delete match history
        $stmt = $conn->prepare(
            "DELETE FROM match_request WHERE receiver_id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // 2️⃣ Delete transplant history (if exists)
        $stmt = $conn->prepare(
            "DELETE FROM transplant_info WHERE receiver_id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // 3️⃣ Delete receiver
        $stmt = $conn->prepare(
            "DELETE FROM users
             WHERE user_id = ? AND role = 'receiver'"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // COMMIT
        $conn->commit();

        header("Location: manage_receiver.php?deleted=1");
        exit;

    } catch (Exception $e) {
        // ROLLBACK ON FAILURE
        $conn->rollback();
        header("Location: manage_receiver.php?error=delete_failed");
        exit;
    }
}

/* ===== SEARCH RECEIVERS ===== */
$search = $_GET['search'] ?? '';
$like = "%$search%";

$stmt = $conn->prepare(
    "SELECT user_id, name, phone_no, email, status
     FROM users
     WHERE role='receiver'
     AND (name LIKE ? OR phone_no LIKE ?)"
);
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$receivers = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Receivers</title>

    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f7fb;
        }

        .container {
            width: 90%;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #3498db;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 14px;
            font-weight: bold;
            font-size: 13px;
        }

        .active {
            background: #eafaf1;
            color: #27ae60;
        }

        .inactive {
            background: #fdecea;
            color: #c0392b;
        }

        .actions a {
            margin-right: 10px;
            font-weight: bold;
            text-decoration: none;
        }

        .activate {
            color: green;
        }

        .deactivate {
            color: orange;
        }

        .delete {
            color: red;
        }

        .msg-success {
            color: green;
            margin: 10px 0;
        }

        .msg-error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Manage Receivers</h2>

        <?php if (isset($_GET['deleted'])) { ?>
            <p class="msg-success">
                ✅ Receiver and all related records deleted permanently.
            </p>
        <?php } ?>

        <?php if (isset($_GET['error'])) { ?>
            <p class="msg-error">
                ❌ Deletion failed. Database rollback applied.
            </p>
        <?php } ?>

        <form method="GET">
            <label><strong>Search Receiver by Name or Phone:</strong></label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" required>
            <button type="submit">Search</button>
        </form>

        <table>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = $receivers->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <span class="badge <?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td class="actions">
                        <?php if ($row['status'] === 'active') { ?>
                            <a class="deactivate" href="?deactivate=<?php echo $row['user_id']; ?>">
                                Deactivate
                            </a>
                        <?php } else { ?>
                            <a class="activate" href="?activate=<?php echo $row['user_id']; ?>">
                                Activate
                            </a>
                        <?php } ?>
                        |
                        <a class="delete" href="?delete=<?php echo $row['user_id']; ?>"
                            onclick="return confirm(
           'WARNING!\n\nThis will permanently delete:\n- Receiver profile\n- Match history\n- Transplant history\n\nThis action CANNOT be undone.\n\nProceed?');">
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