<?php
/**
 * Central Authentication & Authorization
 * Used by admin, donor, receiver pages
 */

/* ===== START SESSION SAFELY ===== */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Role-based access control
 *
 * Usage:
 *   requireRole(['admin']);
 *   requireRole(['donor']);
 *   requireRole(['admin','donor','receiver']);
 */
function requireRole(array $allowed_roles)
{
    // Not logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: /hla_system/public/login.php");
        exit;
    }

    // Role not allowed
    if (!in_array($_SESSION['role'], $allowed_roles, true)) {
        header("Location: /hla_system/public/access_denied.php");
        exit;
    }
}
