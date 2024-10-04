<?php
// Helper function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

// Helper function to check if the user is an admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Helper function to redirect to login page if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../user/login.php');
        exit;
    }
}

// Helper function to restrict access to admin-only pages
function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        header('Location: ../user/login.php');
        exit;
    }
}
?>
