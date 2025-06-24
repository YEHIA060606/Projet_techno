<?php
require_once 'includes/auth.php';
include 'includes/navbar.php';

// Redirection intelligente
if ($_SESSION['role'] === 'admin') {
    header('Location: admin/admin_dashboard.php');
    exit;
} else {
    header('Location: dashboard.php');
    exit;
}
?>
