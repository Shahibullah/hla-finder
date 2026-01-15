<?php
// Only admin can enter HLA typing
include("../includes/auth.php");
requireRole(['admin']);

include("../config/db.php");
include("../includes/header.php");

$error = "";
$success = "";

/* ======================
   HANDLE FORM SUBMISSION
   ====================== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_POST['user_id'];
    $gene_id = $_POST['gene_id'];
    $allele1 = $_POST['allele1'];
    $allele2 = $_POST['allele2'];

    if (empty($user_id) || empty($gene_id) || empty($allele1) || empty($allele2)) {
        $error = "All fields are required.";
    } else {

        // Create sample
        mysqli_query(
            $conn,
            "INSERT INTO sample (user_id, collection_date, tissue_type, lab_id)
             VALUES ('$user_id', CURDATE(), 'Blood', 1)"
        );

        $sample_id = mysqli_insert_id($conn);

        // Create typing record
        mysqli_query(
            $conn,
            "INSERT INTO hla_typing (sample_id, typing_method, typing_date, lab_id)
             VALUES ('$sample_id', 'PCR', CURDATE(), 1)"
        );

        $typing_id = mysqli_insert_id($conn);

        // Store typing result
        mysqli_query(
            $conn,
            "INSERT INTO hla_typing_result
             (typing_id, gene_id, allele1_id, allele2_id)
             VALUES ('$typing_id', '$gene_id', '$allele1', '$allele2')"
        );

        $success = "HLA typing recorded successfully.";
    }
}

/* ======================
   FETCH DATA FOR FORM
   ====================== */

// Donors & Receivers
$users = mysqli_query(
    $conn,
    "SELECT user_id, name, role FROM users
     WHERE role IN ('donor','receiver') AND status='active'"
);

// HLA Genes
$genes = mysqli_query(
    $conn,
    "SELECT gene_id, gene_name FROM hla_gene"
);

// Alleles
$alleles = mysqli_query(
    $conn,
    "SELECT allele_id, allele_name FROM hla_alleles"
);
?>

<div class="container">
    <h2>HLA Typing Form</h2>

    <?php
    if ($error)
        echo "<div class='error'>$error</div>";
    if ($success)
        echo "<div class='success'>$success</div>";
    ?>

    <form method="POST">

        <!-- User -->
        <label>Select Person</label>
        <select name="user_id" required>
            <option value="">Select</option>
            <?php while ($u = mysqli_fetch_assoc($users)) { ?>
                <option value="<?php echo $u['user_id']; ?>">
                    <?php echo $u['name'] . " (" . $u['role'] . ")"; ?>
                </option>
            <?php } ?>
        </select>

        <!-- Gene -->
        <label>HLA Gene</label>
        <select name="gene_id" required>
            <option value="">Select</option>
            <?php while ($g = mysqli_fetch_assoc($genes)) { ?>
                <option value="<?php echo $g['gene_id']; ?>">
                    <?php echo $g['gene_name']; ?>
                </option>
            <?php } ?>
        </select>

        <!-- Allele 1 -->
        <label>Allele 1</label>
        <select name="allele1" required>
            <option value="">Select</option>
            <?php mysqli_data_seek($alleles, 0);
            while ($a = mysqli_fetch_assoc($alleles)) { ?>
                <option value="<?php echo $a['allele_id']; ?>">
                    <?php echo $a['allele_name']; ?>
                </option>
            <?php } ?>
        </select>

        <!-- Allele 2 -->
        <label>Allele 2</label>
        <select name="allele2" required>
            <option value="">Select</option>
            <?php mysqli_data_seek($alleles, 0);
            while ($a = mysqli_fetch_assoc($alleles)) { ?>
                <option value="<?php echo $a['allele_id']; ?>">
                    <?php echo $a['allele_name']; ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit">Save HLA Typing</button>
    </form>
</div>

<?php include "../includes/footer.php"; ?>