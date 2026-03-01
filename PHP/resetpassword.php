<?php
require_once __DIR__ . '/../includes/db.php';

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        $message = "Please enter your email.";
    } else {
        $pdo = getPDO();

        // Check if user exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $message = "No account found with that email.";
        } else {
            // Generate temporary password
            $tempPassword = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 8);
            $tempHash = password_hash($tempPassword, PASSWORD_DEFAULT);

            // Update DB
            $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
            $update->execute([$tempHash, $email]);

            $message = "Your password has been reset.<br><br>
                        Temporary password: <strong>$tempPassword</strong><br>
                        Please log in and change it immediately.";
            $isSuccess = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../js/resetpassword.css">
</head>
<body>

<header class="navbar">
    <div class="logo">Smart Plate</div>
    <nav class="nav-links">
        <a href="Home.html">Home</a>
        <a href="Plan.html">Plan</a>
        <a href="login.php">Sign In</a>
        <a href="Pre-Meals.html">Pre-Meals</a>
    </nav>
</header>

<main class="main-container">
    <div class="form-card">
        <h1 class="header-title">Reset Password</h1>

        <!-- SHOW MESSAGE IF EXISTS -->
        <?php if ($message !== ''): ?>
            <div
                    style="
                            background-color: <?= $isSuccess ? '#d4edda' : '#f8d7da' ?>;
                            padding: 15px;
                            border-radius: 5px;
                            color: <?= $isSuccess ? '#155724' : '#721c24' ?>;
                            margin-bottom: 20px;
                            "
            >
                <?= $message ?>
            </div>
        <?php endif; ?>

        <!-- ONLY SHOW FORM IF NOT SUCCESS -->
        <?php if (!$isSuccess): ?>
            <form action="resetpassword.php" method="POST">
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <button type="submit" class="action-btn">Send Reset Link</button>
            </form>
        <?php else: ?>
            <div style="text-align:center; margin-top:20px;">
                <a href="login.php" class="action-btn"
                   style="display:inline-block; text-decoration:none; width:auto; padding:10px 20px; font-size:18px;">
                    Return to Login
                </a>
            </div>
        <?php endif; ?>

        <?php if (!$isSuccess): ?>
            <div class="footer-links">
                <a href="login.php">Back to Login</a>
            </div>
        <?php endif; ?>
    </div>
</main>

</body>
</html>