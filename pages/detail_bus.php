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

// Get bus ID from URL
$bus_id = $_GET['id'] ?? '';

// Fetch bus details from database
if (!empty($bus_id)) {
    $sql = "SELECT * FROM buses WHERE id = :bus_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':bus_id', $bus_id, PDO::PARAM_INT);
    $stmt->execute();
    $bus = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$bus) {
        echo "Bus tidak ditemukan.";
        exit;
    }
} else {
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Bus - Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Include Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Main Section -->
    <main>
        <section class="detail-bus">
            <h2>Detail Bus</h2>

            <div class="bus-detail-card">
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
                <p><strong>Total Kursi:</strong> <?php echo htmlspecialchars($bus['total_seats']); ?></p>
                <p><strong>Fasilitas:</strong> <?php echo htmlspecialchars($bus['facilities']); ?></p>
                <p><strong>Tentang Bus:</strong> <?php echo htmlspecialchars($bus['about']); ?></p>
                <p><strong>Ketentuan dan Syarat Perjalanan:</strong> <?php echo htmlspecialchars($bus['terms']); ?></p>
                
                <a href="#" id="seat-selection-btn" class="button">Pesan</a>
                <a href="search.php" class="button">Kembali</a>
            </div>
        </section>

        <!-- Modal for Seat Selection -->
        <div id="seat-selection-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Pilih Kursi</h3>
                    <span class="close" id="close-modal">&times;</span>
                </div>
                <div class="modal-body">
                    <label for="seat-dropdown">Nomor Kursi:</label>
                    <select id="seat-dropdown">
                        <?php
                        // Generate seat dropdown options
                        for ($i = 1; $i <= $bus['total_seats']; $i++) {
                            // Assume booked seats are stored in a database table (not shown in this script)
                            // Here we're just making all seats available for the sake of the example
                            echo "<option value='$i'>Kursi $i</option>";
                        }
                        ?>
                    </select>

                    <p><strong>Total Harga:</strong> Rp<?php echo number_format($bus['price'], 0, ',', '.'); ?></p>
                </div>
                <div class="modal-footer">
                    <button class="button" id="proceed-payment">Lanjutkan Pesanan</button>
                    <button class="button" id="cancel-modal-btn">Cancel</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Include Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Script to handle modal -->
    <script>
        // Get modal elements
        const modal = document.getElementById('seat-selection-modal');
        const btn = document.getElementById('seat-selection-btn');
        const closeBtn = document.getElementById('close-modal');
        const cancelBtn = document.getElementById('cancel-modal-btn');

        // Open the modal
        btn.onclick = function() {
            modal.style.display = 'block';
        }

        // Close the modal
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }

        cancelBtn.onclick = function() {
            modal.style.display = 'none';
        }

        // Close the modal if clicked outside of it
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
