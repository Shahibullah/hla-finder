<?php
// Protect page: RECEIVER ONLY
include("../includes/auth.php");
requireRole(['receiver']);

include("../config/db.php");
include("../includes/header.php");

$receiver_id = $_SESSION['user_id'];

// Match request ID required
if (!isset($_GET['match_request_id'])) {
    die("Match request ID is missing.");
}

$match_request_id = (int) $_GET['match_request_id'];

/* =========================
   VERIFY MATCH REQUEST
   (belongs to this receiver)
   ========================= */

$requestStmt = $conn->prepare(
    "SELECT mr.match_request_id, u.name AS receiver_name
     FROM match_request mr
     JOIN users u ON mr.receiver_id = u.user_id
     WHERE mr.match_request_id = ?
       AND mr.receiver_id = ?"
);
$requestStmt->bind_param("ii", $match_request_id, $receiver_id);
$requestStmt->execute();
$request = $requestStmt->get_result()->fetch_assoc();

if (!$request) {
    die("Unauthorized access to match results.");
}

/* =========================
   FETCH MATCH RESULTS
   ========================= */

$resultStmt = $conn->prepare(
    "SELECT u.name AS donor_name, mr.score, mr.match_level
     FROM match_result mr
     JOIN users u ON mr.donor_id = u.user_id
     WHERE mr.match_request_id = ?
     ORDER BY mr.score DESC"
);
$resultStmt->bind_param("i", $match_request_id);
$resultStmt->execute();
$results = $resultStmt->get_result();
?>

<div class="dashboard-container">

    <h2>Match Results</h2>

    <p>
        <strong>Receiver:</strong>
        <?php echo htmlspecialchars($request['receiver_name']); ?>
    </p>

    <hr class="dashboard-divider">

    <?php if ($results->num_rows === 0) { ?>

        <p>No match results found yet.</p>
        <p>Please wait for the administrator to run the matching process.</p>

    <?php } else { ?>

        <table class="dashboard-table">
            <tr>
                <th>Donor Name</th>
                <th>Score</th>
                <th>Match Level</th>
            </tr>

            <?php while ($row = $results->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                    <td><?php echo $row['score']; ?></td>
                    <td><?php echo ucfirst($row['match_level']); ?></td>
                </tr>
            <?php } ?>
        </table>

    <?php } ?>

    <br>

    <a href="/hla_system/receiver/dashboard.php" class="btn-primary">
        ⬅ Back to Receiver Dashboard
    </a>

</div>

<?php include("../includes/footer.php"); ?>