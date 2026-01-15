<?php
session_start();
include("../config/db.php");

/* ===== PROTECT LAB ===== */
if (!isset($_SESSION['lab_id'])) {
    header("Location: lab_login.php");
    exit;
}

$search = "";
$result = null;
$message = "";
$message_type = "";

/* ===== SEARCH ===== */
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $like = "%$search%";

    $stmt = $conn->prepare(
        "SELECT user_id, role, name, phone_no, HLA_Type, HLA_Class
         FROM users
         WHERE role IN ('donor','receiver')
         AND (name LIKE ? OR phone_no LIKE ?)"
    );
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
}

/* ===== UPDATE HLA TYPE + CLASS ===== */
if (isset($_POST['update_hla'])) {

    $user_id = (int) $_POST['user_id'];
    $hla_type = trim($_POST['HLA_Type']);
    $hla_class = $_POST['HLA_Class'];

    if ($hla_type === "" || $hla_class === "") {
        $message = "HLA Type and Class are required.";
        $message_type = "error";
    } else {

        $prefix = strtoupper(substr($hla_type, 0, 5));

        if (in_array($prefix, ['HLA-A', 'HLA-B', 'HLA-C']) && $hla_class !== 'Class-i') {
            $message = "HLA-A, HLA-B, HLA-C must be saved as Class-i.";
            $message_type = "error";
        } elseif ($prefix === 'HLA-D' && $hla_class !== 'Class-ii') {
            $message = "HLA-D must be saved as Class-ii.";
            $message_type = "error";
        } else {

            $stmt = $conn->prepare(
                "UPDATE users
                 SET HLA_Type = ?, HLA_Class = ?
                 WHERE user_id = ?"
            );
            $stmt->bind_param("ssi", $hla_type, $hla_class, $user_id);

            if ($stmt->execute()) {
                $message = "HLA Type and Class saved successfully.";
                $message_type = "success";
            } else {
                $message = "Failed to save HLA information.";
                $message_type = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Set HLA Type</title>

    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-top: 0;
            color: #2c3e50;
        }

        /* ===== SEARCH ===== */
        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            padding: 8px;
            width: 240px;
        }

        .search-box button {
            padding: 8px 14px;
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-box button:hover {
            background: #2980b9;
        }

        /* ===== MESSAGE ===== */
        .msg-success {
            color: #27ae60;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .msg-error {
            color: #c0392b;
            margin-bottom: 15px;
            font-weight: bold;
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
            border-bottom: 1px solid #ddd;
        }

        input[type="text"],
        select {
            padding: 7px;
            width: 100%;
        }

        .save-btn {
            padding: 8px 16px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .save-btn:hover {
            background: #1e8449;
        }

        /* ===== BACK LINK ===== */
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Set Donor / Receiver HLA Type</h2>

        <div class="search-box">
            <form method="GET">
                <label><strong>Search by Name or Phone:</strong></label><br>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" required>
                <button type="submit">Search</button>
            </form>
        </div>

        <?php
        if ($message) {
            echo "<div class='msg-$message_type'>$message</div>";
        }
        ?>

        <?php if ($result && $result->num_rows > 0) { ?>

            <table>
                <tr>
                    <th>Role</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>HLA Type</th>
                    <th>HLA Class</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo ucfirst($row['role']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone_no']); ?></td>

                        <td>
                            <form method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                <input type="text" name="HLA_Type" value="<?php echo htmlspecialchars($row['HLA_Type']); ?>"
                                    placeholder="HLA-A*02:01" required>
                        </td>

                        <td>
                            <select name="HLA_Class" required>
                                <option value="">-- Select --</option>
                                <option value="Class-i" <?php if ($row['HLA_Class'] == 'Class-i')
                                    echo 'selected'; ?>>Class-i
                                </option>
                                <option value="Class-ii" <?php if ($row['HLA_Class'] == 'Class-ii')
                                    echo 'selected'; ?>>Class-ii
                                </option>
                            </select>
                        </td>

                        <td>
                            <button type="submit" name="update_hla" class="save-btn">Save</button>
                        </td>
                        </form>
                    </tr>
                <?php } ?>

            </table>

        <?php } elseif ($search !== "") { ?>
            <p>No donor or receiver found.</p>
        <?php } ?>

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

    </div>

</body>

</html>