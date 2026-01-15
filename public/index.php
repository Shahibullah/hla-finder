<?php
include("../config/db.php");
include("../includes/header.php");

/* ===== COUNT ACTIVE DONORS ===== */
$donorResult = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM users WHERE role='donor' AND status='active'"
);
$donorCount = mysqli_fetch_assoc($donorResult)['total'];
?>

<div class="home-wrapper">

        <!-- WEBSITE TITLE -->
        <h1 class="home-title">HLA FINDER</h1>

        <p class="home-subtitle">
                A secure platform for donor–receiver matching and transplant management
        </p>

        <!-- STATS -->
        <div class="stats-box">
                <p><strong>Total Donors:</strong> <?php echo $donorCount; ?></p>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="home-actions">
                <a href="login.php" class="btn-primary">Login</a>
                <a href="register.php" class="btn-secondary">Register</a>
        </div>

</div>

<!-- ABOUT US SECTION -->
<section class="about-section">

        <h2>About Us</h2>
        <p class="about-text">
                <strong>HLA FINDER</strong> is a healthcare-focused web application designed
                to assist hospitals and clinicians in identifying compatible donors for
                organ and tissue transplantation. The system securely manages HLA data
                and improves coordination between donors, receivers, and medical staff.
        </p>

        <h2>
                <a href="why_hla_typing_is_important.php" style="color:#3498db;">
                        Why HLA typing is so important?
                </a>
        </h2>

        <h2>Conditions of Use</h2>
        <ul class="policy-list">
                <li>Only authorized users may access the system</li>
                <li>All medical and personal data must remain confidential</li>
                <li>Role-based access control is strictly enforced</li>
                <li>The system must be used ethically and responsibly</li>
                <li>Final transplant decisions remain with medical professionals</li>
        </ul>

        <!-- LAB ACCESS SECTION -->
        <section class="lab-section">

                <h2>Laboratory Access</h2>

                <p class="lab-text">
                        This section is <strong>only for authorized laboratories</strong>.
                        Registered labs can securely manage donor samples, update test results,
                        and support accurate HLA typing for transplantation.
                </p>

                <div class="lab-box">
                        <p><strong>🔬 Access restricted to Lab Personnel</strong></p>
                        <h2>
                                <a href="../lab/lab_login.php" class="lab-btn">
                                        Lab Login
                                </a>
                        </h2>
                </div>

        </section>

        <h2>Contact Us</h2>
        <ul class="policy-list">

                <li>Email: admin3@bracu.com</li>
                <li>Contact No: +8809638929150</li>
        </ul>

</section>

<?php include "../includes/footer.php"; ?>