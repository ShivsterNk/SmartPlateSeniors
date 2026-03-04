<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter email and password.';
    } else {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT user_id, name, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = 'Invalid email or password.';
        } else {
            $_SESSION['user_id'] = (int)$user['user_id'];
            $_SESSION['user_name'] = $user['name'];

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
    <title>Smart Plate - Login</title>
    <link rel="stylesheet" href="../js/login.css">
</head>
<body>

<header class="navbar">
    <div class="logo">Smart Plate</div>
    <nav class="nav-links">
        <a href="/index.php">Home</a>
        <a href="features.php" class="active">Features</a>
        <a href="login.php">Sign In</a>
        <a href="../Pre-Meals.html">Pre-Meals</a>
    </nav>
</header>

<main class="main-container">
    <div class="form-card">
        <h1 class="header-title">Login</h1>

        <!-- Show error if any -->
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="input-group">
                <label>Email Address</label>
                <input
                        type="email"
                        name="email"
                        placeholder="Type your Email Address"
                        value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                        required
                >
            </div>

            <div class="input-group">
                <label>Password</label>

                <div class="password-wrapper">
                    <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Type your password"
                            required
                    >

                    <!-- EYE ICON -->
                    <svg id="togglePassword" class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24" fill="none" stroke="#283618" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </div>
            </div>

            <div class="forgot-pwd">
                <a href="resetpassword.php">Forgot password?</a>
            </div>

            <button type="submit" class="action-btn">Login</button>
        </form>

        <div class="footer-links">
            <p>Don't have a login?</p>
            <a href="signup.php">Sign up here</a>
        </div>
    </div>
</main>

<!-- PASSWORD TOGGLE -->
<script>
    function toggleVisibility() {
        const field = document.getElementById("password");
        const icon  = document.getElementById("togglePassword");

        if (field.type === "password") {
            field.type = "text";
            icon.style.opacity = "0.5";  // dim when showing
        } else {
            field.type = "password";
            icon.style.opacity = "1";
        }
    }

    document.getElementById("togglePassword").onclick = toggleVisibility;
</script>

</body>
</html>