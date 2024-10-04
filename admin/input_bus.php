<?php
// Start session and include necessary files
session_start();
require_once '../helpers/auth.php';
require_once '../config/db.php';

// Check if user is logged in and is an admin
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit;
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $departure_date = $_POST['departure_date'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];
    $total_seats = $_POST['total_seats'];
    $facilities = $_POST['facilities'];
    $about = $_POST['about'];
    $terms = $_POST['terms'];

    // Handle bus logo upload
    $logo = $_FILES['logo']['name'];
    $target_dir = "../assets/img/";
    $target_file = $target_dir . basename($logo);

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
        // Insert bus data into database
        $sql = "INSERT INTO buses (name, class, departure_date, departure_time, arrival_time, price, total_seats, facilities, about, terms, logo)
                VALUES (:name, :class, :departure_date, :departure_time, :arrival_time, :price, :total_seats, :facilities, :about, :terms, :logo)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':class' => $class,
            ':departure_date' => $departure_date,
            ':departure_time' => $departure_time,
            ':arrival_time' => $arrival_time,
            ':price' => $price,
            ':total_seats' => $total_seats,
            ':facilities' => $facilities,
            ':about' => $about,
            ':terms' => $terms,
            ':logo' => $logo
        ]);

        $message = "Data bus berhasil ditambahkan!";
    } else {
        $message = "Gagal mengupload logo bus!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Bus - Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Include Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Input Bus Form -->
    <main>
        <section class="input-bus">
            <h2>Input Data Bus</h2>

            <?php if ($message): ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>

            <form action="input_bus.php" method="POST" enctype="multipart/form-data">
                <label for="logo">Logo Bus (SVG):</label>
                <input type="file" name="logo" id="logo" required>

                <label for="name">Nama Bus:</label>
                <input type="text" name="name" id="name" required>

                <label for="class">Kelas:</label>
                <input type="text" name="class" id="class" required>

                <label for="departure_date">Tanggal Keberangkatan:</label>
                <input type="date" name="departure_date" id="departure_date" required>

                <label for="departure_time">Jam Keberangkatan:</label>
                <input type="time" name="departure_time" id="departure_time" required>

                <label for="arrival_time">Jam Sampai:</label>
                <input type="time" name="arrival_time" id="arrival_time" required>

                <label for="price">Harga (Rp):</label>
                <input type="number" name="price" id="price" required>

                <label for="total_seats">Total Kursi:</label>
                <input type="number" name="total_seats" id="total_seats" required>

                <label for="facilities">Fasilitas:</label>
                <input type="text" name="facilities" id="facilities" required>

                <label for="about">Tentang Bus:</label>
                <textarea name="about" id="about" rows="4" required></textarea>

                <label for="terms">Ketentuan dan Syarat Perjalanan:</label>
                <textarea name="terms" id="terms" rows="4" required></textarea>

                <button type="submit">Submit</button>
                <div>
                    <p>asdadsasd</p>
                </div>
            </form>
        </section>
    </main>

    <!-- Include Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
