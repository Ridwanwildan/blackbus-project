<?php
// Start session and include necessary files
session_start();
require_once '../helpers/auth.php';

// Check if user is logged in and is an admin
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Include Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Admin Dashboard Content -->
    <main>
        <section class="dashboard">
            <h2>Admin Dashboard</h2>
            <p>Selamat datang di halaman admin, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            
            <div class="dashboard-actions">
                <a href="input_bus.php" class="button">Input Data Bus</a>
            </div>
        </section>
    </main>

    <!-- Include Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
