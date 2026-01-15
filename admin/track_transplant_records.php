<?php
// Protect page: ADMIN ONLY
include("../includes/auth.php");
requireRole(['admin']);

include("../config/db.php");
include("../includes/header.php");

/* ===== SEARCH LOGIC ===== */
$search = trim($_GET['search'] ?? '');

$query = "
    SELECT 
        t.transplant_id,
        r.name AS receiver_name,
        d.name AS donor_name,
        t.organ_type,
        t.transplant_date,
        t.outcome
    FROM transplant_info t
    JOIN users r ON t.receiver_id = r.user_id
    JOIN users d ON t.donor_id = d.user_id
";

if ($search !== '') {
    $query .= " 
        WHERE r.name LIKE ? 
           OR d.name LIKE ?
    ";
    $stmt = $conn->prepare($query);
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = mysqli_query($conn, $query);
}
?>

<div class="dashboard-container">

    <h2>Transplant Records</h2>
    <p>View and search transplant history</p>

    <hr class="dashboard-divider">

    <!-- SEARCH FORM -->
    <form method="GET" style="margin-bottom:20px;">
        <input type="text" name="search" placeholder="Search by Donor or Receiver name"
            value="<?php echo htmlspecialchars($search); ?>" style="padding:8px; width:250px;">
        <button type="submit">Search</button>
        <a href="track_transplant_records.php" style="margin-left:10px;">Reset</a>
    </form>

    <!-- TRANSPLANT TABLE -->
    <table class="dashboard-table">
        <tr>
            <th>ID</th>
            <th>Receiver</th>
            <th>Donor</th>
            <th>Organ</th>
            <th>Date</th>
            <th>Outcome</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['transplant_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['receiver_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['organ_type']); ?></td>
                    <td><?php echo $row['transplant_date']; ?></td>
                    <td><?php echo ucfirst($row['outcome']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">
                    No transplant records found.
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <p style="margin-top:20px;">
        <a href="dashboard.php">← Back to Admin Dashboard</a>
    </p>

</div>

<?php include "../includes/footer.php"; ?>