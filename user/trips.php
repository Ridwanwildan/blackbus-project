<?php
// Start session and include necessary files
session_start();
require_once '../helpers/auth.php';
require_once '../config/db.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get user ID from session
$user_id = $_SESSION['id'];

// Fetch trips data from database
$sql = "SELECT b.logo, b.name, b.class, t.departure_date, t.departure_time, t.arrival_time, t.price 
        FROM trips t
        JOIN buses b ON t.bus_id = b.id
        WHERE t.user_id = :user_id AND t.payment_status = 'paid'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perjalanan Saya - Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Include Header -->
    <?php include '../includes/header.php'; ?>

    <main>
        <section class="trips">
            <h2>Perjalanan Saya</h2>

            <?php if (count($trips) > 0): ?>
                <div class="trip-list">
                    <?php foreach ($trips as $trip): ?>
                        <div class="trip-card">
                            <img src="../assets/img/<?php echo htmlspecialchars($trip['logo']); ?>" alt="Logo Bus">
                            <h3><?php echo htmlspecialchars($trip['name']); ?> (<?php echo htmlspecialchars($trip['class']); ?>)</h3>
                            <p><strong>Tanggal Keberangkatan:</strong> <?php echo htmlspecialchars($trip['departure_date']); ?></p>
                            <p><strong>Jam Keberangkatan:</strong> <?php echo htmlspecialchars($trip['departure_time']); ?></p>
                            <p><strong>Jam Sampai:</strong> <?php echo htmlspecialchars($trip['arrival_time']); ?></p>
                            <p><strong>Harga:</strong> Rp<?php echo number_format($trip['price'], 0, ',', '.'); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Anda belum memiliki perjalanan yang dibayar.</p>
            <?php endif; ?>
        </section>
    </main>

    <!-- Include Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
