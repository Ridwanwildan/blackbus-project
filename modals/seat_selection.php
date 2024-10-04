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

// Get bus ID and user ID from session or URL
$bus_id = $_GET['bus_id'] ?? '';
$user_id = $_SESSION['id'];

// Fetch bus details from database
$sql = "SELECT total_seats, price FROM buses WHERE id = :bus_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':bus_id', $bus_id, PDO::PARAM_INT);
$stmt->execute();
$bus = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bus) {
    echo "Bus tidak ditemukan.";
    exit;
}

// Fetch already booked seats
$sql = "SELECT seat_number FROM bookings WHERE bus_id = :bus_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':bus_id', $bus_id, PDO::PARAM_INT);
$stmt->execute();
$booked_seats = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Kursi - Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Modal for Seat Selection -->
    <div id="seat-selection-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Pilih Kursi</h3>
                <span class="close" id="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <label for="seat-dropdown">Nomor Kursi:</label>
                <select id="seat-dropdown" name="seat_number">
                    <?php
                    // Generate seat dropdown options excluding already booked seats
                    for ($i = 1; $i <= $bus['total_seats']; $i++) {
                        if (!in_array($i, $booked_seats)) {
                            echo "<option value='$i'>Kursi $i</option>";
                        }
                    }
                    ?>
                </select>

                <p><strong>Total Harga:</strong> Rp<?php echo number_format($bus['price'], 0, ',', '.'); ?></p>
            </div>
            <div class="modal-footer">
                <form action="../payments/midtrans.php" method="POST">
                    <input type="hidden" name="bus_id" value="<?php echo $bus_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="price" value="<?php echo $bus['price']; ?>">
                    <input type="hidden" name="seat_number" id="selected-seat">
                    <button type="submit" class="button" id="proceed-payment">Lanjutkan Pesanan</button>
                </form>
                <button class="button" id="cancel-modal-btn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Script to handle modal and form -->
    <script>
        // Get modal elements
        const modal = document.getElementById('seat-selection-modal');
        const closeBtn = document.getElementById('close-modal');
        const cancelBtn = document.getElementById('cancel-modal-btn');
        const proceedBtn = document.getElementById('proceed-payment');
        const seatDropdown = document.getElementById('seat-dropdown');
        const selectedSeatInput = document.getElementById('selected-seat');

        // Close the modal
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }

        cancelBtn.onclick = function() {
            modal.style.display = 'none';
        }

        // Set selected seat in form before submitting
        proceedBtn.onclick = function() {
            selectedSeatInput.value = seatDropdown.value;
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
