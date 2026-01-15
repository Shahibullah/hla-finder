<?php
// Protect page: DONOR ONLY
include("../includes/auth.php");
requireRole(['donor']);

include("../config/db.php");
include("../includes/header.php");

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

/* ===== FETCH USER INFO ===== */
$userQuery = mysqli_query(
    $conn,
    "SELECT name, phone_no, address_by_divisions
     FROM users
     WHERE user_id = $user_id"
);
$user = mysqli_fetch_assoc($userQuery);

/* ===== UPDATE LOGIC ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $division = trim($_POST['division']);

    if ($name === "" || strlen($name) > 20) {
        $error = "Name must be within 20 characters.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE users
             SET name = ?, phone_no = ?, address_by_divisions = ?
             WHERE user_id = ?"
        );
        $stmt->bind_param("sssi", $name, $phone, $division, $user_id);

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<div class="dashboard-container">

    <h2>Update Personal Information</h2>

    <?php
    if ($error)
        echo "<p style='color:red;'>$error</p>";
    if ($success)
        echo "<p style='color:green;'>$success</p>";
    ?>

    <form method="POST" class="register-wrapper">

        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" maxlength="20" required>

        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone_no']); ?>"
            placeholder="Phone Number">

        <select name="division" required>
            <option value="">Select Division</option>
            <?php
            $divisions = [
                'Dhaka',
                'Chittagong',
                'Khulna',
                'Rajshahi',
                'Barisal',
                'Sylhet',
                'Rangpur',
                'Mymensingh'
            ];
            foreach ($divisions as $d) {
                $selected = ($user['address_by_divisions'] === $d) ? "selected" : "";
                echo "<option value='$d' $selected>$d</option>";
            }
            ?>
        </select>

        <button type="submit">Update Profile</button>
    </form>

    <br>
    <a href="dashboard.php" class="btn-primary">⬅ Back to Dashboard</a>

</div>

<?php include "../includes/footer.php"; ?>