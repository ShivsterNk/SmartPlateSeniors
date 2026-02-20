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
    <title>Password Reset</title>
    <!-- match the login page CSS path -->
    <link rel="stylesheet" href="../login.css">
</head>
<body>

<main class="main-container">
    <div class="form-card">
        <h1 class="header-title">Password Reset</h1>

        <?php if (!empty($message)): ?>
            <div class="<?php echo $isSuccess ? 'success-message' : 'error-message'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (!$isSuccess): ?>
            <form method="post" action="resetpassword.php">
                <div class="input-group">
                    <label for="email">Enter your account email</label>
                    <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Type your email address"
                            required
                    >
                </div>

                <button type="submit" class="action-btn">Send Reset</button>
            </form>
        <?php endif; ?>

        <br>
        <a href="login.php" class="action-btn secondary-btn">Return to Login</a>
    </div>
</main>

</body>
</html>