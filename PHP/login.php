<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /SmartPlateSeniors/PHP/dashboard.php");
    exit;
}

require_once __DIR__ . '/../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter email and password.';
    } else {
        $pdo  = getPDO();
        $stmt = $pdo->prepare("SELECT user_id, name, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = 'Invalid email or password.';
        } else {
            $_SESSION['user_id']   = (int)$user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: /SmartPlateSeniors/PHP/dashboard.php");
            exit;
        }
    }
}

include('../includes/header.php');
?>

    <style>
        /* ── Page layout ── */
        .login-page {
            flex: 1;
            margin-top: 70px;
            display: flex;
            align-items: stretch;
            font-family: 'DM Sans', sans-serif;
        }

        /* ── Left panel — decorative green side ── */
        .login-left {
            flex: 1;
            background: linear-gradient(160deg, #1a2e10 0%, #283618 50%, #3a5a20 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }

        /* decorative circles */
        .login-left::before,
        .login-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(167,201,87,0.15);
        }
        .login-left::before {
            width: 500px; height: 500px;
            top: -100px; left: -100px;
        }
        .login-left::after {
            width: 300px; height: 300px;
            bottom: -60px; right: -60px;
        }

        .login-left-content { position: relative; z-index: 1; text-align: center; }

        .login-brand-icon {
            font-size: 3.5rem; margin-bottom: 24px;
            display: block;
        }
        .login-brand-title {
            font-family: 'DM Serif Display', serif;
            font-size: 2.2rem; color: #fff;
            line-height: 1.15; margin-bottom: 16px;
        }
        .login-brand-title em { color: #a7c957; font-style: italic; }
        .login-brand-sub {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.75; max-width: 300px;
            margin: 0 auto 40px;
        }

        /* feature pill list */
        .login-features {
            display: flex; flex-direction: column; gap: 12px;
            text-align: left;
        }
        .login-feature-item {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 12px 16px;
            color: rgba(255,255,255,0.8);
            font-size: 0.88rem;
        }
        .login-feature-item span:first-child { font-size: 1.2rem; }

        /* ── Right panel — form ── */
        .login-right {
            flex: 1;
            background: #f5f2ec;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
        }

        .login-card h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem; color: #1a2e10;
            margin: 0 0 6px;
        }
        .login-card .login-tagline {
            font-size: 0.9rem; color: #7a8a7a;
            margin-bottom: 32px;
        }

        /* error */
        .error-message {
            background: #fff0f0;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .error-message::before { content: '⚠️'; }

        /* form fields */
        .lf-group { margin-bottom: 20px; }
        .lf-label {
            display: block;
            font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: #283618; margin-bottom: 7px;
        }
        .lf-input {
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
        .lf-input:focus {
            border-color: #283618;
            box-shadow: 0 0 0 3px rgba(40,54,24,0.08);
        }
        .lf-input::placeholder { color: #b0bfb0; }

        /* password wrapper */
        .lf-pw-wrap { position: relative; }
        .lf-pw-toggle {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; padding: 0;
            color: #7a8a7a; font-size: 1rem;
            line-height: 1;
        }
        .lf-pw-toggle:hover { color: #283618; }

        /* forgot link */
        .lf-forgot {
            text-align: right; margin-bottom: 24px; margin-top: -12px;
        }
        .lf-forgot a {
            font-size: 0.82rem; color: #4a7c4a;
            text-decoration: none; font-weight: 600;
        }
        .lf-forgot a:hover { text-decoration: underline; }

        /* submit button */
        .lf-btn {
            width: 100%;
            background: #283618; color: white;
            border: none; border-radius: 10px;
            padding: 14px; font-size: 1rem;
            font-weight: 700; cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background 0.2s, transform 0.15s;
            margin-bottom: 20px;
        }
        .lf-btn:hover {
            background: #1a2e10;
            transform: translateY(-2px);
        }

        /* divider */
        .lf-divider {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 20px; color: #b0bfb0; font-size: 0.8rem;
        }
        .lf-divider::before,
        .lf-divider::after {
            content: ''; flex: 1;
            height: 1px; background: #e0e8d8;
        }

        /* signup link */
        .lf-signup {
            text-align: center;
            font-size: 0.88rem; color: #7a8a7a;
        }
        .lf-signup a {
            color: #283618; font-weight: 700;
            text-decoration: none;
        }
        .lf-signup a:hover { text-decoration: underline; }

        /* ── Responsive: stack on mobile ── */
        @media (max-width: 768px) {
            .login-page { flex-direction: column; }
            .login-left { padding: 48px 24px; flex: none; }
            .login-features { display: none; }
            .login-right { padding: 40px 20px; }
        }
    </style>

    <main style="margin-top:0; flex:1; display:flex; flex-direction:column;">
        <div class="login-page">

            <!-- ── Left decorative panel ── -->
            <div class="login-left">
                <div class="login-left-content">
                    <span class="login-brand-icon">🥗</span>
                    <h2 class="login-brand-title">
                        Welcome back to<br><em>SmartPlate.</em>
                    </h2>
                    <p class="login-brand-sub">
                        Your nutrition dashboard, AI assistant, and recipe generator are waiting.
                    </p>
                    <div class="login-features">
                        <div class="login-feature-item">
                            <span>🤖</span>
                            <span>Chat with PlateBot AI anytime</span>
                        </div>
                        <div class="login-feature-item">
                            <span>📊</span>
                            <span>Track your daily nutrition</span>
                        </div>
                        <div class="login-feature-item">
                            <span>📖</span>
                            <span>Discover thousands of recipes</span>
                        </div>
                        <div class="login-feature-item">
                            <span>🛒</span>
                            <span>Auto-generate shopping lists</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Right form panel ── -->
            <div class="login-right">
                <div class="login-card">

                    <h1>Sign In</h1>
                    <p class="login-tagline">Enter your details to access your account.</p>

                    <?php if (!empty($error)): ?>
                        <div class="error-message"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">

                        <div class="lf-group">
                            <label class="lf-label" for="email">Email Address</label>
                            <input
                                    class="lf-input"
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="you@example.com"
                                    value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                                    required
                                    autocomplete="email"
                            >
                        </div>

                        <div class="lf-group">
                            <label class="lf-label" for="password">Password</label>
                            <div class="lf-pw-wrap">
                                <input
                                        class="lf-input"
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Enter your password"
                                        required
                                        autocomplete="current-password"
                                >
                                <button type="button" class="lf-pw-toggle" id="togglePassword">👁️</button>
                            </div>
                        </div>

                        <div class="lf-forgot">
                            <a href="resetpassword.php">Forgot password?</a>
                        </div>

                        <button type="submit" class="lf-btn">Sign In</button>

                    </form>

                    <div class="lf-divider">or</div>

                    <div class="lf-signup">
                        Don't have an account? <a href="signup.php">Sign up free</a>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const field = document.getElementById('password');
            if (field.type === 'password') {
                field.type = 'text';
                this.textContent = '🙈';
            } else {
                field.type = 'password';
                this.textContent = '👁️';
            }
        });
    </script>

<?php include('../includes/footer.php'); ?>