<?php
include("../includes/lab_auth.php");
include("../config/db.php");
include("../includes/header.php");

$q = $_GET['q'] ?? '';

$sql = "
SELECT user_id, name, phone_no, role
FROM users
WHERE (name LIKE ? OR phone_no LIKE ?)
";

$stmt = $conn->prepare($sql);
$search = "%$q%";
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="dashboard-container">
    <h2>Search Donor / Receiver</h2>

    <form method="GET">
        <input type="text" name="q" placeholder="Name or Phone" value="<?php echo htmlspecialchars($q); ?>">
        <button type="submit">Search</button>
    </form>

    <table class="dashboard-table">
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Role</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['phone_no']; ?></td>
                <td><?php echo ucfirst($row['role']); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include("../includes/footer.php"); ?>