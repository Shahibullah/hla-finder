<?php
session_start();
include("../config/db.php");

/* ===== PROTECT RECEIVER ===== */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receiver') {
    header("Location: ../public/login.php");
    exit;
}

$receiver_id = $_SESSION['user_id'];

/* ======================
   HLA MATCH FUNCTION
   ====================== */
function calculateHLAMatch($receiver, $donor)
{
    if (!$receiver || !$donor)
        return 0;

    preg_match('/^(HLA-[A-Z0-9]+)\*(\d+):(\d+)/', $receiver, $r);
    preg_match('/^(HLA-[A-Z0-9]+)\*(\d+):(\d+)/', $donor, $d);

    if (!$r || !$d)
        return 0;

    // Gene must match
    if ($r[1] !== $d[1])
        return 0;

    $percentage = 40; // gene match

    if ($r[2] === $d[2]) {
        $percentage = 70; // allele group
    }

    if ($r[2] === $d[2] && substr($r[3], 0, 1) === substr($d[3], 0, 1)) {
        $percentage = 90; // partial allele
    }

    if ($receiver === $donor) {
        $percentage = 100; // full match
    }

    return $percentage;
}

/* ===== GET RECEIVER HLA ===== */
$stmt = $conn->prepare(
    "SELECT HLA_Type FROM users WHERE user_id = ?"
);
$stmt->bind_param("i", $receiver_id);
$stmt->execute();
$stmt->bind_result($receiver_hla);
$stmt->fetch();
$stmt->close();

$matches = [];

if ($receiver_hla) {

    /* ===== GET ALL DONORS (ACTIVE + INACTIVE) ===== */
    $stmt = $conn->prepare(
        "SELECT name, phone_no, email, address_by_divisions,
                HLA_Type, status
         FROM users
         WHERE role = 'donor'
         AND HLA_Type IS NOT NULL"
    );
    $stmt->execute();
    $result = $stmt->get_result();

    while ($donor = $result->fetch_assoc()) {

        $percentage = calculateHLAMatch(
            $receiver_hla,
            $donor['HLA_Type']
        );

        if ($percentage > 0) {
            $matches[] = [
                'name' => $donor['name'],
                'phone' => $donor['phone_no'],
                'email' => $donor['email'],
                'address' => $donor['address_by_divisions'],
                'hla' => $donor['HLA_Type'],
                'status' => $donor['status'],
                'percent' => $percentage
            ];
        }
    }

    // Sort by highest match
    usort($matches, function ($a, $b) {
        return $b['percent'] <=> $a['percent'];
    });
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>HLA Match Status</title>

    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f7fb;
        }

        .container {
            width: 90%;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
        }

        .match-box {
            background: #f9fbfd;
            padding: 18px;
            border-left: 6px solid #3498db;
            margin-bottom: 18px;
            border-radius: 8px;
        }

        .match-100 {
            border-color: #27ae60;
        }

        .match-90 {
            border-color: #f39c12;
        }

        .match-70 {
            border-color: #3498db;
        }

        .match-40 {
            border-color: #7f8c8d;
        }

        .status-active {
            color: #27ae60;
            font-weight: bold;
        }

        .status-inactive {
            color: #c0392b;
            font-weight: bold;
        }

        h2 {
            color: #2c3e50;
        }

        .info-row {
            margin: 4px 0;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>HLA Match Status</h2>

        <p><strong>Your HLA Type:</strong>
            <?php echo htmlspecialchars($receiver_hla); ?>
        </p>

        <hr>

        <?php if (!empty($matches)) { ?>

            <?php foreach ($matches as $m) { ?>

                <div class="match-box match-<?php echo $m['percent']; ?>">

                    <?php if ($m['percent'] == 100) { ?>
                        ✅ <strong>Perfect Match!</strong><br>
                    <?php } ?>

                    <strong><?php echo $m['percent']; ?>%</strong>
                    HLA match with <strong><?php echo htmlspecialchars($m['name']); ?></strong>

                    <div class="info-row">
                        <strong>HLA Type:</strong> <?php echo htmlspecialchars($m['hla']); ?>
                    </div>

                    <div class="info-row">
                        <strong>Phone:</strong> <?php echo htmlspecialchars($m['phone']); ?>
                    </div>

                    <div class="info-row">
                        <strong>Email:</strong> <?php echo htmlspecialchars($m['email']); ?>
                    </div>

                    <div class="info-row">
                        <strong>Address:</strong> <?php echo htmlspecialchars($m['address']); ?>
                    </div>

                    <div class="info-row">
                        <strong>Status:</strong>
                        <span class="status-<?php echo $m['status']; ?>">
                            <?php echo ucfirst($m['status']); ?> Donor
                        </span>
                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p>No matching donors found at this time.</p>

        <?php } ?>

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

    </div>

</body>

</html>