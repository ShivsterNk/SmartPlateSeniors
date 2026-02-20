<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $confirm === '') {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $pdo = getPDO();

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Email already exists. Please log in.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                    "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)"
            );
            $stmt->execute([$name, $email, $hash]);

            $_SESSION['user_id']   = (int)$pdo->lastInsertId();
            $_SESSION['user_name'] = $name;

            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Plate - Sign Up</title>
    <link rel="stylesheet" href="../js/signup.css">
</head>
<body>

<header class="navbar">
    <div class="logo">Smart Plate</div>
    <nav class="nav-links">
        <a href="#">Home</a>
        <a href="#">Plan</a>
        <a href="login.php">Sign In</a>
        <a href="#">Pre-Meals</a>
    </nav>
</header>

<div class="main-container">
    <div class="form-card">

        <h1 class="header-title">Create Your Account</h1>

        <!-- PHP Error Display -->
        <?php if (!empty($error)): ?>
            <p style="color: red; margin-bottom: 15px; font-weight: bold;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>


        <form method="POST">

            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>

            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password" required>
            </div>

            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
            </div>

            <button type="submit" class="action-btn">Create Account</button>

        </form>

        <div class="footer-links">
            <p>Already have an account? <a href="login.php">Sign In</a></p>
        </div>

    </div>
</div>

</body>
</html>
