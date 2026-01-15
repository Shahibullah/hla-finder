<?php
session_start();
include("../config/db.php");

/* ===== PROTECT LAB ===== */
if (!isset($_SESSION['lab_id'])) {
    header("Location: ../lab/lab_login.php");
    exit;
}

$success = "";
$error = "";

/* ===============================
   DONOR HLA SEARCH (TOP)
   =============================== */
$search_hla = trim($_GET['search_hla'] ?? '');
$hla_like = "%" . $search_hla . "%";

if ($search_hla !== "") {
    $stmt = $conn->prepare(
        "SELECT user_id, name, HLA_Type
         FROM users
         WHERE role='donor'
           AND status='active'
           AND HLA_Type IS NOT NULL
           AND HLA_Type LIKE ?"
    );
    $stmt->bind_param("s", $hla_like);
    $stmt->execute();
    $donors = $stmt->get_result();
} else {
    $donors = $conn->query(
        "SELECT user_id, name, HLA_Type
         FROM users
         WHERE role='donor'
           AND status='active'
           AND HLA_Type IS NOT NULL"
    );
}

/* ===============================
   RECEIVER SEARCH (NAME / PHONE)
   =============================== */
$receiver_search = trim($_GET['receiver_search'] ?? '');
$receiver_like = "%" . $receiver_search . "%";

if ($receiver_search !== "") {
    $stmt = $conn->prepare(
        "SELECT user_id, name, phone_no, HLA_Type, HLA_Class
         FROM users
         WHERE role='receiver'
           AND status='active'
           AND (name LIKE ? OR phone_no LIKE ?)"
    );
    $stmt->bind_param("ss", $receiver_like, $receiver_like);
    $stmt->execute();
    $receivers = $stmt->get_result();
} else {
    $receivers = $conn->query(
        "SELECT user_id, name, phone_no, HLA_Type, HLA_Class
         FROM users
         WHERE role='receiver'
           AND status='active'
           AND HLA_Type IS NOT NULL"
    );
}

/* ===============================
   MATCH PERCENTAGE LOGIC
   =============================== */
function calculateMatch($receiver, $donor)
{
    if (!$receiver || !$donor)
        return 0;

    preg_match('/^(HLA-[A-Z0-9]+)\*(\d+):(\d+)/', $receiver, $r);
    preg_match('/^(HLA-[A-Z0-9]+)\*(\d+):(\d+)/', $donor, $d);

    if (!$r || !$d)
        return 0;
    if ($r[1] !== $d[1])
        return 0;

    $percent = 40;
    if ($r[2] === $d[2])
        $percent = 70;
    if ($r[2] === $d[2] && substr($r[3], 0, 1) === substr($d[3], 0, 1))
        $percent = 90;
    if ($receiver === $donor)
        $percent = 100;

    return $percent;
}

/* ===============================
   MATCH PREVIEW
   =============================== */
$match_preview = null;

if (!empty($_POST['donor_id']) && !empty($_POST['receiver_id'])) {

    $stmt = $conn->prepare("SELECT HLA_Type FROM users WHERE user_id=?");
    $stmt->bind_param("i", $_POST['donor_id']);
    $stmt->execute();
    $stmt->bind_result($donor_hla);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT HLA_Type FROM users WHERE user_id=?");
    $stmt->bind_param("i", $_POST['receiver_id']);
    $stmt->execute();
    $stmt->bind_result($receiver_hla);
    $stmt->fetch();
    $stmt->close();

    $match_preview = calculateMatch($receiver_hla, $donor_hla);
}

/* ===============================
   SAVE TRANSPLANT
   =============================== */
if (isset($_POST['save'])) {

    $donor_id = (int) $_POST['donor_id'];
    $receiver_id = (int) $_POST['receiver_id'];
    $organ = $_POST['organ'];
    $outcome = $_POST['outcome'];
    $date = $_POST['transplant_date'];

    if (!$donor_id || !$receiver_id || !$organ || !$outcome || !$date) {
        $error = "All fields are required.";
    } else {

        $stmt = $conn->prepare("SELECT HLA_Class FROM users WHERE user_id=?");
        $stmt->bind_param("i", $receiver_id);
        $stmt->execute();
        $stmt->bind_result($hla_class);
        $stmt->fetch();
        $stmt->close();

        if ($organ === "Stem Cell" && $hla_class !== "Class-ii") {
            $error = "Stem Cell transplant is allowed only for Class-ii HLA.";
        } else {

            $stmt = $conn->prepare(
                "INSERT INTO transplant_info
                 (receiver_id, donor_id, transplant_date, organ_type, outcome)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "iisss",
                $receiver_id,
                $donor_id,
                $date,
                $organ,
                $outcome
            );

            if ($stmt->execute()) {
                $success = "Transplantation record saved successfully.";
            } else {
                $error = "Failed to save transplant record.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Transplantation Action</title>

    <style>
        body {
            font-family: Segoe UI, Arial;
            background: #f4f7fb;
        }

        .container {
            width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 35px;
            border-radius: 14px;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
            margin-top: 6px;
        }

        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background: #27ae60;
            color: #fff;
            border: none;
        }

        .match-box {
            background: #eef7ff;
            border-left: 6px solid #3498db;
            padding: 12px;
            margin: 15px 0;
            font-weight: bold;
        }

        .success {
            color: #27ae60;
            font-weight: bold;
        }

        .error {
            color: #c0392b;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Transplantation Action</h2>

        <form method="GET">
            <label>Search Donor by HLA Type</label>
            <input type="text" name="search_hla" placeholder="e.g. HLA-A*01"
                value="<?php echo htmlspecialchars($search_hla); ?>">
        </form>

        <form method="GET">
            <input type="hidden" name="search_hla" value="<?php echo htmlspecialchars($search_hla); ?>">
            <label>Search Receiver by Name or Phone</label>
            <input type="text" name="receiver_search" placeholder="Type receiver name or phone"
                value="<?php echo htmlspecialchars($receiver_search); ?>">
            <button type="submit">Search Receiver</button>
        </form>

        <?php if ($match_preview !== null) { ?>
            <div class="match-box">
                Match Percentage Preview: <?php echo $match_preview; ?>%
            </div>
        <?php } ?>

        <?php if ($error)
            echo "<p class='error'>$error</p>"; ?>
        <?php if ($success)
            echo "<p class='success'>$success</p>"; ?>

        <form method="POST">

            <label>Select Donor</label>
            <select name="donor_id" required>
                <option value="">-- Select Donor --</option>
                <?php while ($d = $donors->fetch_assoc()) { ?>
                    <option value="<?php echo $d['user_id']; ?>">
                        <?php echo htmlspecialchars($d['name'] . " (" . $d['HLA_Type'] . ")"); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Select Receiver</label>
            <select name="receiver_id" required>
                <option value="">-- Select Receiver --</option>
                <?php while ($r = $receivers->fetch_assoc()) { ?>
                    <option value="<?php echo $r['user_id']; ?>">
                        <?php echo htmlspecialchars($r['name'] . " (" . $r['HLA_Type'] . ")"); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Organ</label>
            <select name="organ" required>
                <option>Kidney</option>
                <option>Heart</option>
                <option>Lung</option>
                <option>Liver</option>
                <option>Pancreas</option>
                <option>Cornea (Eye)</option>
                <option>Bone Marrow</option>
                <option>Stem Cell</option>
            </select>

            <label>Outcome</label>
            <select name="outcome" required>
                <option>Ongoing</option>
                <option>Successful</option>
                <option>Rejected</option>
            </select>

            <label>Transplant Date</label>
            <input type="date" name="transplant_date" required>

            <button type="submit" name="save">Save Transplant</button>

        </form>

        <a href="../lab/dashboard.php">← Back to Lab Dashboard</a>

    </div>
</body>

</html>