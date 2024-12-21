<?php
session_start();

// Define static password for admin
$static_password = "GDPARTSTUDIO";

// Check if user is authenticated as admin
function isAdminAuthenticated()
{
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

// Redirect to login page if not authenticated
function requireAdminAuth()
{
    if (!isAdminAuthenticated()) {
        header("Location: index.php"); // Redirect to login
        exit;
    }
}
