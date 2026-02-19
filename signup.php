<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/includes/db.php';

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
    <link rel="stylesheet" href="js/signup.css"><!-- your CSS file -->
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

<main class="content-wrapper">
    <section class="form-card">

        <p class="sub-header">Create Account</p>
        <h1 class="main-header">Join Smart Plate</h1>

        <!-- show backend errors if any -->
        <?php if ($error): ?>
            <p style="color:#b00020; font-weight:bold; margin-bottom:20px;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <div class="input-box">
                <label for="name">Full Name</label>
                <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter your full name"
                        required
                >
            </div>

            <div class="input-box">
                <label for="email">Email Address</label>
                <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                >
            </div>

            <div class="input-box">
                <label for="password">Password</label>
                <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create a password"
                        required
                >
            </div>

            <div class="input-box">
                <label for="confirm_password">Confirm Password</label>
                <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Re-enter your password"
                        required
                >
            </div>

            <div class="button-container">
                <button type="submit" class="submit-button">
                    Create Account
                </button>
            </div>

            <p style="margin-top:15px;">
                Already have an account?
                <a href="login.php">Sign in</a>
            </p>
        </form>
    </section>
</main>

</body>
</html>
