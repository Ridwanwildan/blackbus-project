<?php
// Start session and include necessary files
session_start();
require_once '../helpers/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../user/login.php');
    exit;
}

// Get username from session
$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Include Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Main Section -->
    <main>
        <section class="welcome">
            <h2>Selamat Datang di Blackbus, <?php echo htmlspecialchars($username); ?>!</h2>
            <p>Pesan tiket bus Anda dengan mudah dan cepat melalui Blackbus!</p>
        </section>

        <!-- Search Bus Form -->
        <section class="search">
            <h3>Cari Bus</h3>
            <form action="search.php" method="GET">
                <label for="origin">Kota Asal:</label>
                <input type="text" name="origin" id="origin" required>

                <label for="destination">Kota Tujuan:</label>
                <input type="text" name="destination" id="destination" required>

                <label for="departure-date">Tanggal Keberangkatan:</label>
                <input type="date" name="departure_date" id="departure-date" required>

                <button type="submit" id="search-btn">Cari</button>
            </form>
        </section>

        <!-- Promo Section -->
        <section class="promo">
            <h3>Promo Menarik</h3>
            <p>Dapatkan diskon hingga 50% untuk pembelian tiket bus di bulan ini!</p>
        </section>

        <!-- How to Order Section -->
        <section class="how-to-order">
            <h3>Cara Pemesanan</h3>
            <ol>
                <li>Masukkan kota asal, kota tujuan, dan tanggal keberangkatan di form pencarian bus.</li>
                <li>Pilih bus yang sesuai dari hasil pencarian.</li>
                <li>Pilih kursi yang diinginkan dan lanjutkan ke pembayaran.</li>
                <li>Dapatkan tiket elektronik Anda setelah pembayaran berhasil.</li>
            </ol>
        </section>
    </main>

    <!-- Include Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
