<?php
// ================== ADMIN PROTECTION ==================
include("../includes/auth.php");
requireRole(['admin']);

include("../config/db.php");
include("../includes/header.php");

// ================== ADD LAB LOGIC ==================
$lab_success = "";
$lab_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_lab'])) {

    $lab_name = trim($_POST['lab_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($lab_name === '' || $email === '') {
        $lab_error = "Lab name and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $lab_error = "Invalid email format.";
    } else {

        // Check duplicate email
        $check = $conn->prepare("SELECT lab_id FROM lab WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $lab_error = "Lab email already exists.";
        } else {

            // Generate temporary password
            $temp_password = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 8);
            $password_hash = password_hash($temp_password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO lab (lab_name, address, email, password_hash, status)
                 VALUES (?, ?, ?, ?, 'active')"
            );
            $stmt->bind_param("ssss", $lab_name, $address, $email, $password_hash);

            if ($stmt->execute()) {
                $lab_success = "Lab added successfully. <br>
                               <strong>Temporary Password:</strong> $temp_password";
            } else {
                $lab_error = "Database error while adding lab.";
            }
        }
    }
}

// ================== SYSTEM STATISTICS ==================
$donorCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='donor'")
)['total'];

$receiverCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='receiver'")
)['total'];

$labCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM lab")
)['total'];
?>

<div class="dashboard-container">

    <h2>Admin Dashboard</h2>
    <p>Welcome, <strong>System Administrator</strong></p>

    <hr class="dashboard-divider">

    <!-- ================== SYSTEM OVERVIEW ================== -->
    <h3>System Overview</h3>

    <table class="dashboard-table">
        <tr>
            <th>Total Donors</th>
            <th>Total Receivers</th>
            <th>Total Labs</th>
        </tr>
        <tr>
            <td><?php echo $donorCount; ?></td>
            <td><?php echo $receiverCount; ?></td>
            <td><?php echo $labCount; ?></td>
        </tr>
    </table>

    <hr class="dashboard-divider">



    <!-- ================== MANAGEMENT ================== -->
    <h3>System Management</h3>
    <ul class="dashboard-list">
        <li><a href="manage_donors.php">Manage Donors</a></li>
        <li><a href="manage_receiver.php">Manage Receivers</a></li>

        <li><a href="manage_labs.php">Manage Labs</a></li>
        <li><a href="track_transplant_records.php">Track Transplant Records</a></li>
    </ul>

    <hr class="dashboard-divider">

    <!-- ================== ADMIN ACTIONS ================== -->
    <h3>Admin Actions</h3>
    <ul class="dashboard-list">
        <li><a href="../public/change_password.php">Change Password</a></li>
        <li><a href="../public/index.php">Home</a></li>
        <li><a href="../public/logout.php">Logout</a></li>
    </ul>

    <p class="dashboard-note">
        ⚠ This panel is accessible to authorized administrators only.
    </p>

</div>

<?php include("../includes/footer.php"); ?>