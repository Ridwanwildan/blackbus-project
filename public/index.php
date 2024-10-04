<?php
// Start session and include necessary files
session_start();
require_once '../helpers/auth.php';

// Check if the user is already logged in
if (isLoggedIn()) {
    // Redirect to the home page if the user is logged in
    header('Location: ../pages/home.php');
    exit;
} else {
    // Redirect to the login page if the user is not logged in
    header('Location: ../user/login.php');
    exit;
}
?>
