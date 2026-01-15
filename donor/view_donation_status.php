<?php
// Protect page: DONOR ONLY
include("../includes/auth.php");
requireRole(['donor']);

include("../config/db.php");
include("../includes/header.php");

$user_id = $_SESSION['user_id'];

/* ===== FETCH USER STATUS ===== */
$userQuery = mysqli_query(
    $conn,
    "SELECT name, status FROM users WHERE user_id = $user_id"
);
$user = mysqli_fetch_assoc($userQuery);

/* ===== FETCH MATCH RESULT ===== */
$matchQuery = mysqli_query(
    $conn,
    "SELECT match_level, score
     FROM match_result
     WHERE donor_id = $user_id"
);

/* ===== FETCH TRANSPLANT INFO ===== */
$transplantQuery = mysqli_query(
    $conn,
    "SELECT transplant_date, organ_type, outcome
     FROM transplant_info
     WHERE donor_id = $user_id"
);
?>

<div class="dashboard-container">

    <h2>Donation Status</h2>
    <p>Donor: <strong><?php echo htmlspecialchars($user['name']); ?></strong></p>

    <hr class="dashboard-divider">

    <h3>Account Status</h3>
    <p>
        Status:
        <strong style="color:<?php echo $user['status'] === 'active' ? 'green' : 'red'; ?>">
            <?php echo ucfirst($user['status']); ?>
        </strong>
    </p>



    <a href="dashboard.php" class="btn-primary">⬅ Back to Dashboard</a>


</div>

<?php include "../includes/footer.php"; ?>