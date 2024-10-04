<?php

// Start session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Start session and include authentication helper
session_start();
require_once '../helpers/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../user/login.php');
    exit;
}

// Get user data
$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/main.js" defer></script>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>Blackbus - Aplikasi Pemesanan Tiket Bus Online</h1>
        <div class="user-info">
            <span>Selamat datang, <?php echo htmlspecialchars($username); ?>!</span>
            <div class="dropdown">
                <button class="dropbtn">Menu</button>
                <div class="dropdown-content">
                    <a href="../user/trips.php">Perjalanan Saya</a>
                    <?php if ($role === 'admin'): ?>
                        <a href="../admin/dashboard.php" id="admin-link">Admin Dashboard</a>
                    <?php endif; ?>
                    <a href="../user/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>
</body>
</html>
