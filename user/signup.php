<?php
// Start session and include necessary files
session_start();
require_once '../config/db.php';

// Define variables and initialize with empty values
$name = $username = $email = $phone = $password = "";
$name_err = $username_err = $email_err = $phone_err = $password_err = $signup_err = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate full name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your full name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Check if username is already taken
        $sql = "SELECT id FROM users WHERE username = :username";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST["username"]);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                $signup_err = "Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate phone number
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check for input errors before inserting into the database
    if (empty($name_err) && empty($username_err) && empty($email_err) && empty($phone_err) && empty($password_err)) {
        // Insert the new user into the database
        $sql = "INSERT INTO users (name, username, email, phone, password, role) VALUES (:name, :username, :email, :phone, :password, 'user')";
        
        if ($stmt = $pdo->prepare($sql)) {
            // Bind parameters
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":phone", $param_phone, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            // Set parameters
            $param_name = $name;
            $param_username = $username;
            $param_email = $email;
            $param_phone = $phone;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Password hashing

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect to login page
                header("Location: login.php");
                exit;
            } else {
                $signup_err = "Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }

    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Signup Form -->
    <main>
        <section class="signup">
            <h2>Sign Up</h2>
            
            <?php if ($signup_err): ?>
                <p class="error-message"><?php echo $signup_err; ?></p>
            <?php endif; ?>
            
            <form action="signup.php" method="POST">
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
                <span class="error"><?php echo $name_err; ?></span>

                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <span class="error"><?php echo $username_err; ?></span>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <span class="error"><?php echo $email_err; ?></span>

                <label for="phone">Phone Number:</label>
                <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                <span class="error"><?php echo $phone_err; ?></span>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <span class="error"><?php echo $password_err; ?></span>

                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </section>
    </main>
</body>
</html>
