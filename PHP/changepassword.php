<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Only logged-in users can access this page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($current === '' || $new === '' || $confirm === '') {
        $message = "Please fill out all fields.";
    } elseif ($new !== $confirm) {
        $message = "New passwords do not match.";
    } elseif (strlen($new) < 6) {
        $message = "New password should be at least 6 characters.";
    } else {
        $pdo = getPDO();

        // Get existing password hash
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($current, $user['password_hash'])) {
            $message = "Current password is incorrect.";
        } else {
            // Update to new password
            $newHash = password_hash($new, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $update->execute([$newHash, $userId]);

            $isSuccess = true;
            $message   = "Your password has been updated successfully.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Plate - Change Password</title>
    <link rel="stylesheet" href="../js/login.css">
</head>
<body>

<header class="navbar">
    <div class="logo">Smart Plate</div>
    <nav class="nav-links">
        <a href="dashboard.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="smartmeals.php">Ready Meals</a>
        <a href="logout.php">Log out</a>
    </nav>
</header>

<main class="main-container">
    <div class="form-card">
        <h1 class="header-title">Change Password</h1>

        <?php if ($message !== ''): ?>
            <div
                style="
                    background-color: <?= $isSuccess ? '#d4edda' : '#f8d7da' ?>;
                    padding: 12px;
                    border-radius: 5px;
                    color: <?= $isSuccess ? '#155724' : '#721c24' ?>;
                    margin-bottom: 20px;
                    font-size: 14px;
                    "
            >
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (!$isSuccess): ?>
            <form method="POST">
                <div class="input-group">
                    <label for="current_password">Current Password</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        placeholder="Enter your current password"
                        required
                    >
                </div>

                <div class="input-group">
                    <label for="new_password">New Password</label>
                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        placeholder="Enter a new password"
                        required
                    >
                </div>

                <div class="input-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Re-enter the new password"
                        required
                    >
                </div>

                <button type="submit" class="action-btn">Update Password</button>
            </form>
        <?php else: ?>
            <div style="text-align:center; margin-top:10px;">
                <a href="dashboard.php" class="action-btn"
                   style="display:inline-block; text-decoration:none; width:auto; padding:10px 20px; font-size:18px;">
                    Back to Dashboard
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
