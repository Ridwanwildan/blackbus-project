<?php
// Start session and include necessary files
session_start();
require_once '../config/db.php';

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check credentials
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password, role FROM users WHERE username = :username";
        if ($stmt = $pdo->prepare($sql)) {
            // Bind parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = $username;

            // Execute the query
            if ($stmt->execute()) {
                // Check if username exists
                if ($stmt->rowCount() == 1) {
                    // Fetch user data
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        $role = $row["role"];

                        // Verify the password
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;

                            // Redirect user to the home page
                            header("Location: ../pages/home.php");
                            exit;
                        } else {
                            // Password is not valid
                            $login_err = "The password you entered is incorrect.";
                        }
                    }
                } else {
                    // Username doesn't exist
                    $login_err = "No account found with that username.";
                }
            } else {
                $login_err = "Something went wrong. Please try again later.";
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
    <title>Login - Blackbus</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Login Form -->
    <main>
        <section class="login">
            <h2>Login</h2>

            <?php if ($login_err): ?>
                <p class="error-message"><?php echo $login_err; ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <span class="error"><?php echo $username_err; ?></span>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <span class="error"><?php echo $password_err; ?></span>

                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
        </section>
    </main>
</body>
</html>
