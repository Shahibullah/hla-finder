<?php
session_start();
include("../config/db.php");

// OPTIONAL: protect admin
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: ../public/login.php");
//     exit;
// }

$result = mysqli_query($conn, "SELECT * FROM lab");
?>

<h2>Lab Management Dashboard</h2>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Lab Name</th>
        <th>Address</th>
        <th>Email</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php while ($lab = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($lab['lab_name']); ?></td>
            <td><?php echo htmlspecialchars($lab['address']); ?></td>
            <td><?php echo htmlspecialchars($lab['email']); ?></td>
            <td>
                <?php if ($lab['status'] === 'active') { ?>
                    <span style="color:green;">Active</span>
                <?php } else { ?>
                    <span style="color:red;">Inactive</span>
                <?php } ?>
            </td>
            <td>
                <!-- Toggle Status -->
                <a href="update_lab_status.php?id=<?php echo $lab['lab_id']; ?>&status=<?php echo $lab['status']; ?>">
                    <?php echo ($lab['status'] === 'active') ? 'Deactivate' : 'Activate'; ?>
                </a>
                |
                <!-- Delete -->
                <a href="delete_lab.php?id=<?php echo $lab['lab_id']; ?>"
                    onclick="return confirm('Are you sure you want to delete this lab?');" style="color:red;">
                    Delete
                </a>
            </td>
        </tr>
    <?php } ?>
</table>
<style>
    body {
        font-family: "Segoe UI", Tahoma, Arial, sans-serif;
        background: #f1f4f9;
        margin: 0;
    }

    .container {
        width: 90%;
        margin: 40px auto;
        background: #ffffff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    h2 {
        margin-top: 0;
        color: #2c3e50;
    }

    /* ===== TABLE ===== */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th {
        background: #3498db;
        color: #fff;
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
        padding: 6px 12px;
        border-radius: 14px;
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
        margin-right: 12px;
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

    /* ===== BACK LINK ===== */
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