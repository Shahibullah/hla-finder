<?php
session_start();

if (!isset($_SESSION['lab_id']) || $_SESSION['role'] !== 'lab') {
    header("Location: /hla_system/public/login.php");
    exit;
}
