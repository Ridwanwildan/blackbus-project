<?php
// Start session and include necessary files
session_start();
require_once '../helpers/auth.php';
require_once '../config/db.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../user/login.php');
    exit;
}

// Get search parameters
$origin = $_GET['origin'] ?? '';
$destination = $_GET['destination'] ?? '';
$departure_date = $_GET['departure_date'] ?? '';

// Validate search parameters
if (empty($origin) || empty($destination) || empty($departure_date)) {
    header('Location: home.php');
    exit;
}

// Fetch bus data from database based on search parameters
$sql = "SELECT * FROM buses 
        WHERE departure_date = :departure_date 
        AND origin = :origin 
        AND destination = :destination";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':departure_date', $departure_date, PDO::PARAM_STR);
$stmt->bindParam(':origin', $origin, PDO::PARAM_STR);
$stmt->bindParam(':destination', $destination, PDO::PARAM_STR);
$stmt->execute();
$buses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Include Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Main Section -->
    <main>
        <section class="search-results">
            <h2>Hasil Pencarian</h2>
            <?php if (count($buses) > 0): ?>
                <div class="bus-list">
                    <?php foreach ($buses as $bus): ?>
                        <div class="bus-card">
                            <img src="../assets/img/<?php echo htmlspecialchars($bus['logo']); ?>" alt="Logo Bus">
                            <h3><?php echo htmlspecialchars($bus['name']); ?> (<?php echo htmlspecialchars($bus['class']); ?>)</h3>
                            <p><strong>Keberangkatan:</strong> <?php echo htmlspecialchars($bus['departure_time']); ?></p>
                            <p><strong>Sampai:</strong> <?php echo htmlspecialchars($bus['arrival_time']); ?></p>
                            <p><strong>Lama Perjalanan:</strong> 
                                <?php 
                                // Hitung lama perjalanan
                                $departure_time = new DateTime($bus['departure_time']);
                                $arrival_time = new DateTime($bus['arrival_time']);
                                $interval = $departure_time->diff($arrival_time);
                                echo $interval->format('%h jam %i menit');
                                ?>
                            </p>
                            <p><strong>Harga:</strong> Rp<?php echo number_format($bus['price'], 0, ',', '.'); ?></p>
                            <p><strong>Fasilitas:</strong> <?php echo htmlspecialchars($bus['facilities']); ?></p>
                            <a href="detail_bus.php?id=<?php echo $bus['id']; ?>" class="button">Pilih</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Data tidak tersedia untuk pencarian yang Anda lakukan.</p>
            <?php endif; ?>
        </section>
    </main>

    <!-- Include Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
