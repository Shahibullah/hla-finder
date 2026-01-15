<?php
// RECEIVER ONLY
include("../includes/auth.php");
requireRole(['receiver']);

include("../config/db.php");
include("../includes/header.php");

$error = "";
$success = "";

/* ======================
   HANDLE FORM SUBMISSION
   ====================== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $receiver_id = $_SESSION['user_id']; // receiver creates own request
    $genes = $_POST['genes'] ?? [];

    if (empty($genes)) {
        $error = "Please select at least one HLA gene requirement.";
    } else {

        // Create match request
        $stmt = $conn->prepare(
            "INSERT INTO match_request (receiver_id, status)
             VALUES (?, 'request_created')"
        );
        $stmt->bind_param("i", $receiver_id);
        $stmt->execute();

        $match_request_id = $conn->insert_id;

        // Insert gene requirements
        $reqStmt = $conn->prepare(
            "INSERT INTO match_requirements
             (match_request_id, gene_id, requirement_level)
             VALUES (?, ?, ?)"
        );

        foreach ($genes as $gene_id => $level) {
            $reqStmt->bind_param("iis", $match_request_id, $gene_id, $level);
            $reqStmt->execute();
        }

        $success = "Match request created successfully.";
    }
}

/* ======================
   FETCH HLA GENES
   ====================== */

$genesQuery = mysqli_query(
    $conn,
    "SELECT gene_id, gene_name FROM hla_gene ORDER BY gene_name"
);
?>

<div class="container">

    <h2>Create Match Request</h2>

    <?php
    if ($error)
        echo "<div class='error'>$error</div>";
    if ($success)
        echo "<div class='success'>$success</div>";
    ?>

    <form method="POST">

        <h3>HLA Requirements</h3>

        <?php while ($g = mysqli_fetch_assoc($genesQuery)) { ?>
            <div style="margin-bottom:12px;">
                <strong><?php echo htmlspecialchars($g['gene_name']); ?></strong><br>

                <label>
                    <input type="radio" name="genes[<?php echo $g['gene_id']; ?>]" value="mandatory" required>
                    Mandatory
                </label>

                <label>
                    <input type="radio" name="genes[<?php echo $g['gene_id']; ?>]" value="preferred">
                    Preferred
                </label>

                <label>
                    <input type="radio" name="genes[<?php echo $g['gene_id']; ?>]" value="optional">
                    Optional
                </label>
            </div>
        <?php } ?>

        <button type="submit">Create Match Request</button>
    </form>

    <br>

    <a href="/hla_system/receiver/dashboard.php" class="btn-primary">
        ⬅ Back to Receiver Dashboard
    </a>

</div>

<?php include "../includes/footer.php"; ?>