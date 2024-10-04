<?php
// Database configuration for Blackbus

$host = 'localhost';       // Nama host, biasanya 'localhost'
$db_name = 'blackbus';     // Nama database
$username = 'root';        // Username database (default untuk XAMPP adalah 'root')
$password = '';            // Password database (default untuk XAMPP kosong)

// Attempt to connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
