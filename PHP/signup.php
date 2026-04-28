<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

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
        $pdo  = getPDO();
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "An account with that email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
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
    <title>Sign Up | SmartPlate</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/SmartPlateSeniors/assets/spstyle.css">
    <style>
        .signup-page {
            min-height: calc(100vh - 70px);
            margin-top: 70px;
            display: flex;
            align-items: stretch;
            font-family: 'DM Sans', sans-serif;
        }

        /* ── Right decorative panel ── */
        .signup-right {
            flex: 1;
            background: linear-gradient(160deg, #1a2e10 0%, #283618 50%, #3a5a20 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 48px;
            position: relative;
            overflow: hidden;
            order: 2;
        }
        .signup-right::before,
        .signup-right::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(167,201,87,0.15);
        }
        .signup-right::before {
            width: 500px; height: 500px;
            bottom: -100px; right: -100px;
        }
        .signup-right::after {
            width: 280px; height: 280px;
            top: -60px; left: -60px;
        }
        .signup-right-content {
            position: relative; z-index: 1;
            text-align: center;
        }
        .signup-brand-icon { font-size: 3.5rem; margin-bottom: 24px; display: block; }
        .signup-brand-title {
            font-family: 'DM Serif Display', serif;
            font-size: 2.2rem; color: #fff;
            line-height: 1.15; margin-bottom: 16px;
        }
        .signup-brand-title em { color: #a7c957; font-style: italic; }
        .signup-brand-sub {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.75; max-width: 300px;
            margin: 0 auto 40px;
        }

        /* Steps list */
        .signup-steps {
            display: flex; flex-direction: column; gap: 16px;
            text-align: left;
        }
        .signup-step {
            display: flex; align-items: flex-start; gap: 14px;
        }
        .signup-step-num {
            width: 28px; height: 28px; border-radius: 50%;
            background: #a7c957; color: #1a2e10;
            font-weight: 700; font-size: 0.82rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 2px;
        }
        .signup-step-text strong {
            display: block; color: #fff;
            font-size: 0.9rem; margin-bottom: 2px;
        }
        .signup-step-text span {
            font-size: 0.8rem; color: rgba(255,255,255,0.5);
        }

        /* ── Left form panel ── */
        .signup-left {
            flex: 1; order: 1;
            background: #f5f2ec;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 48px;
            overflow-y: auto;
        }

        .signup-card { width: 100%; max-width: 440px; padding: 8px 0; }

        .signup-card h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem; color: #1a2e10;
            margin: 0 0 6px;
        }
        .signup-tagline {
            font-size: 0.9rem; color: #7a8a7a;
            margin-bottom: 28px;
        }

        /* error */
        .sf-error {
            background: #fff0f0;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.88rem; font-weight: 600;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .sf-error::before { content: '⚠️'; }

        /* form fields */
        .sf-group { margin-bottom: 12px; }
        .sf-label {
            display: block;
            font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: #283618; margin-bottom: 7px;
        }
        .sf-input {
            width: 100%;
            border: 2px solid #d8e8d4;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.92rem;
            font-family: 'DM Sans', sans-serif;
            color: #283618;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
            outline: none;
        }
        .sf-input:focus {
            border-color: #283618;
            box-shadow: 0 0 0 3px rgba(40,54,24,0.08);
        }
        .sf-input::placeholder { color: #b0bfb0; }

        /* password wrapper */
        .sf-pw-wrap { position: relative; }
        .sf-pw-toggle {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; padding: 0;
            color: #7a8a7a; font-size: 1rem; line-height: 1;
        }
        .sf-pw-toggle:hover { color: #283618; }

        /* password strength */
        .sf-strength-bar {
            height: 4px; background: #e8f0e8;
            border-radius: 99px; margin-top: 8px; overflow: hidden;
        }
        .sf-strength-fill {
            height: 100%; border-radius: 99px; width: 0%;
            transition: width 0.3s, background 0.3s;
        }
        .sf-strength-label {
            font-size: 0.73rem; color: #7a8a7a;
            margin-top: 4px; min-height: 1em;
        }

        /* submit */
        .sf-btn {
            width: 100%;
            background: #283618; color: white;
            border: none; border-radius: 10px;
            padding: 14px; font-size: 1rem;
            font-weight: 700; cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background 0.2s, transform 0.15s;
            margin-top: 8px; margin-bottom: 20px;
        }
        .sf-btn:hover { background: #1a2e10; transform: translateY(-2px); }

        /* divider + signin link */
        .sf-divider {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 20px; color: #b0bfb0; font-size: 0.8rem;
        }
        .sf-divider::before, .sf-divider::after {
            content: ''; flex: 1; height: 1px; background: #e0e8d8;
        }
        .sf-signin {
            text-align: center;
            font-size: 0.88rem; color: #7a8a7a;
        }
        .sf-signin a {
            color: #283618; font-weight: 700; text-decoration: none;
        }
        .sf-signin a:hover { text-decoration: underline; }

        /* terms note */
        .sf-terms {
            font-size: 0.75rem; color: #b0bfb0;
            text-align: center; margin-top: 16px; line-height: 1.5;
        }

        @media (max-width: 768px) {
            .signup-page { flex-direction: column; }
            .signup-right { order: 1; padding: 40px 24px; flex: none; }
            .signup-steps { display: none; }
            .signup-left { order: 2; padding: 40px 20px; }
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<main style="margin-top:0; flex:1; display:flex; flex-direction:column;">
    <div class="signup-page">

        <!-- ── Left: Form ── -->
        <div class="signup-left">
            <div class="signup-card">

                <h1>Create Account</h1>
                <p class="signup-tagline">Join SmartPlate and start eating smarter today.</p>

                <?php if (!empty($error)): ?>
                    <div class="sf-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">

                    <div class="sf-group">
                        <label class="sf-label" for="name">Full Name</label>
                        <input class="sf-input" type="text" id="name" name="name"
                               placeholder="Lauryn Akinmade" required autocomplete="name">
                    </div>

                    <div class="sf-group">
                        <label class="sf-label" for="email">Email Address</label>
                        <input class="sf-input" type="email" id="email" name="email"
                               placeholder="you@example.com" required autocomplete="email">
                    </div>

                    <div class="sf-group">
                        <label class="sf-label" for="password">Password</label>
                        <div class="sf-pw-wrap">
                            <input class="sf-input" type="password" id="password" name="password"
                                   placeholder="Create a password" required autocomplete="new-password"
                                   oninput="checkStrength(this.value)">
                            <button type="button" class="sf-pw-toggle" onclick="togglePw('password', this)">👁️</button>
                        </div>
                        <div class="sf-strength-bar">
                            <div class="sf-strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="sf-strength-label" id="strengthLabel"></div>
                    </div>

                    <div class="sf-group">
                        <label class="sf-label" for="confirm_password">Confirm Password</label>
                        <div class="sf-pw-wrap">
                            <input class="sf-input" type="password" id="confirm_password"
                                   name="confirm_password" placeholder="Re-enter your password"
                                   required autocomplete="new-password">
                            <button type="button" class="sf-pw-toggle" onclick="togglePw('confirm_password', this)">👁️</button>
                        </div>
                    </div>

                    <button type="submit" class="sf-btn">Create Account</button>

                </form>

                <div class="sf-divider">or</div>
                <div class="sf-signin">
                    Already have an account? <a href="login.php">Sign in</a>
                </div>
                <p class="sf-terms">
                    By creating an account you agree to our Terms of Service and Privacy Policy.
                </p>

            </div>
        </div>

        <!-- ── Right: Decorative ── -->
        <div class="signup-right">
            <div class="signup-right-content">
                <span class="signup-brand-icon">🌿</span>
                <h2 class="signup-brand-title">
                    Start your journey to<br><em>smarter eating.</em>
                </h2>
                <p class="signup-brand-sub">
                    It takes less than a minute to get set up. Here's what happens next:
                </p>
                <div class="signup-steps">
                    <div class="signup-step">
                        <div class="signup-step-num">1</div>
                        <div class="signup-step-text">
                            <strong>Create your account</strong>
                            <span>Just a name, email and password.</span>
                        </div>
                    </div>
                    <div class="signup-step">
                        <div class="signup-step-num">2</div>
                        <div class="signup-step-text">
                            <strong>Set your dietary preferences</strong>
                            <span>Tell us your goals and restrictions.</span>
                        </div>
                    </div>
                    <div class="signup-step">
                        <div class="signup-step-num">3</div>
                        <div class="signup-step-text">
                            <strong>Explore your dashboard</strong>
                            <span>Track meals, chat with PlateBot, discover recipes.</span>
                        </div>
                    </div>
                    <div class="signup-step">
                        <div class="signup-step-num">4</div>
                        <div class="signup-step-text">
                            <strong>Eat smarter every day</strong>
                            <span>SmartPlate grows with your habits over time.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    function togglePw(id, btn) {
        const field = document.getElementById(id);
        field.type = field.type === 'password' ? 'text' : 'password';
        btn.textContent = field.type === 'password' ? '👁️' : '🙈';
    }

    function checkStrength(val) {
        const fill  = document.getElementById('strengthFill');
        const label = document.getElementById('strengthLabel');
        let score = 0;
        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { w: '0%',   c: '#e63946', t: '' },
            { w: '25%',  c: '#e63946', t: 'Weak' },
            { w: '50%',  c: '#f4a261', t: 'Fair' },
            { w: '75%',  c: '#a7c957', t: 'Good' },
            { w: '100%', c: '#283618', t: 'Strong' },
        ];
        fill.style.width      = levels[score].w;
        fill.style.background = levels[score].c;
        label.textContent     = levels[score].t;
        label.style.color     = levels[score].c;
    }

    // Client-side password match check
    document.querySelector('form').addEventListener('submit', function(e) {
        const pw = document.getElementById('password').value;
        const cf = document.getElementById('confirm_password').value;
        if (pw !== cf) {
            e.preventDefault();
            alert('Passwords do not match. Please try again.');
        }
    });
</script>

<?php include('../includes/footer.php'); ?>
</body>
</html>