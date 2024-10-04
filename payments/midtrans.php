<?php
// Start session and include necessary files
session_start();
require_once '../config/db.php';

// Include Midtrans API Library (assumes Midtrans PHP SDK is installed)
require_once 'path/to/midtrans-php/Midtrans.php';

// Set your Midtrans Server Key
\Midtrans\Config::$serverKey = 'your-midtrans-server-key';

// Enable production mode if necessary (default is sandbox mode)
\Midtrans\Config::$isProduction = false; // Set to true for production

// Set sanitization and 3D secure options
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Get data from POST request
$bus_id = $_POST['bus_id'] ?? '';
$user_id = $_POST['user_id'] ?? '';
$seat_number = $_POST['seat_number'] ?? '';
$price = $_POST['price'] ?? '';

// Check if required data is present
if (empty($bus_id) || empty($user_id) || empty($seat_number) || empty($price)) {
    echo "Data pembayaran tidak lengkap.";
    exit;
}

// Fetch user details from database
$sql = "SELECT username, email FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}

// Create transaction details for Midtrans
$transaction_details = [
    'order_id' => 'ORDER-' . time(),  // Unique order ID
    'gross_amount' => (int)$price,    // Total payment amount in IDR
];

// Create item details
$item_details = [
    [
        'id' => $bus_id,
        'price' => (int)$price,
        'quantity' => 1,
        'name' => "Tiket Bus Blackbus"
    ]
];

// Create customer details
$customer_details = [
    'first_name' => $user['username'],
    'email' => $user['email']
];

// Create Midtrans payment parameters
$transaction_data = [
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
    'customer_details' => $customer_details
];

try {
    // Create a Snap transaction and get the payment token
    $snapToken = \Midtrans\Snap::getSnapToken($transaction_data);

    // Store the booking data in the database
    $sql = "INSERT INTO bookings (user_id, bus_id, seat_number, price, payment_status) 
            VALUES (:user_id, :bus_id, :seat_number, :price, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':bus_id' => $bus_id,
        ':seat_number' => $seat_number,
        ':price' => $price
    ]);

    // Redirect to Snap Payment page
    echo "<html><body>";
    echo "<script src='https://app.sandbox.midtrans.com/snap/snap.js' data-client-key='your-midtrans-client-key'></script>";
    echo "<script type='text/javascript'>
            snap.pay('".$snapToken."');
          </script>";
    echo "</body></html>";

} catch (Exception $e) {
    echo "Gagal membuat transaksi: " . $e->getMessage();
}
?>
