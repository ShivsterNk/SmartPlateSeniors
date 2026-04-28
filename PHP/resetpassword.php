<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$message   = '';
$isSuccess = false;
$tempPassword = '';

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
            $tempHash     = password_hash($tempPassword, PASSWORD_DEFAULT);

            // Save to DB
            $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
            $update->execute([$tempHash, $email]);

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
    <title>Reset Password | SmartPlate</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/SmartPlateSeniors/assets/spstyle.css">
    <style>
        body {
            background: #f5f2ec;
            margin: 0;
            font-family: 'DM Sans', sans-serif;
        }

        /* ── Banner ── */
        .page-banner {
            background: linear-gradient(135deg, #1a2e10, #3a5220);
            color: white;
            padding: 14px 0;
            margin-top: 70px;
            border-bottom: 3px solid #a7c957;
            text-align: center;
        }
        .banner-icon { font-size: 2rem; margin-bottom: 8px; }
        .page-banner h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem; font-weight: 400; margin: 0 0 6px;
        }
        .page-banner p { color: rgba(255,255,255,0.7); font-size: 0.9rem; margin: 0; }

        /* ── Card ── */
        .reset-wrap {
            max-width: 640px;
            margin: 48px auto 80px;
            padding: 0 24px;
        }
        .reset-card {
            background: white;
            border-radius: 16px;
            padding: 44px 48px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }

        /* ── Alerts ── */
        .alert-error-sp {
            background: #fff0f0; border: 1px solid #fca5a5;
            color: #b91c1c; border-radius: 10px;
            padding: 12px 16px; font-size: 0.88rem;
            font-weight: 600; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }

        /* ── Temp password reveal box ── */
        .temp-pw-box {
            background: #edf3eb;
            border: 2px solid #a7c957;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
            text-align: center;
        }
        .temp-pw-box p {
            margin: 0 0 10px;
            color: #283618;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .temp-pw-code {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            color: #1a2e10;
            background: white;
            border: 2px dashed #a7c957;
            border-radius: 8px;
            padding: 10px 20px;
            display: inline-block;
            margin-bottom: 12px;
            cursor: pointer;
            user-select: all;
        }
        .copy-hint {
            font-size: 0.78rem;
            color: #7a8a7a;
            margin: 0;
        }
        .copy-hint.copied { color: #283618; font-weight: 700; }

        .instructions {
            background: #fffbe6;
            border-left: 4px solid #f4a261;
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #7a5c00;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        .instructions strong { color: #5a3e00; }

        /* ── Form elements ── */
        .form-label {
            font-weight: 600; font-size: 0.82rem;
            color: #283618; text-transform: uppercase;
            letter-spacing: 0.05em; margin-bottom: 6px;
            display: block;
        }
        .form-control {
            width: 100%;
            border: 2px solid #d8e8d4;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            color: #283618;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        .form-control:focus {
            border-color: #283618;
            box-shadow: none;
            outline: none;
        }
        .mb-3 { margin-bottom: 16px; }

        /* ── Buttons ── */
        .btn-primary-sp {
            background: #283618; color: white;
            border: none; border-radius: 10px;
            padding: 12px 28px; font-weight: 700;
            font-size: 0.9rem; cursor: pointer;
            transition: background 0.2s;
            width: 100%; margin-top: 8px;
            font-family: 'DM Sans', sans-serif;
            text-align: center;
            display: block;
            text-decoration: none;
        }
        .btn-primary-sp:hover { background: #1f2a12; color: white; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            font-size: 0.85rem;
            color: #7a8a7a;
            text-decoration: none;
        }
        .back-link:hover { color: #283618; }

        @media (max-width: 640px) {
            .reset-card { padding: 28px 20px; }
            .temp-pw-code { font-size: 1.3rem; letter-spacing: 0.12em; }
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="page-banner">
    <div class="container text-center">
        <div class="banner-icon">🔑</div>
        <h1>Reset Password</h1>
        <p>Enter your email to receive a temporary password</p>
    </div>
</div>

<div class="reset-wrap">
    <div class="reset-card">

        <?php if (!empty($message) && !$isSuccess): ?>
            <div class="alert-error-sp">⚠️ <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($isSuccess): ?>

            <!-- ── Success: show temp password ── -->
            <div class="temp-pw-box">
                <p>Your temporary password is:</p>
                <div class="temp-pw-code" id="tempPw" onclick="copyPw()" title="Click to copy">
                    <?= htmlspecialchars($tempPassword) ?>
                </div>
                <p class="copy-hint" id="copyHint">Click the password to copy it</p>
            </div>

            <div class="instructions">
                <strong>Next steps:</strong><br>
                1. Copy your temporary password above.<br>
                2. <a href="login.php" style="color:#7a5c00; font-weight:700;">Sign in</a> using your email and this password.<br>
                3. Go to <strong>Profile Settings</strong> and set a new permanent password.
            </div>

            <a href="login.php" class="btn-primary-sp">Go to Login</a>

        <?php else: ?>

            <!-- ── Email form ── -->
            <form action="resetpassword.php" method="POST">
                <div class="mb-3">
                    <label class="form-label" for="email">Email Address</label>
                    <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Enter your account email"
                            required
                            autocomplete="email"
                    >
                </div>
                <button type="submit" class="btn-primary-sp">Get Temporary Password</button>
            </form>

            <a href="login.php" class="back-link">← Back to Login</a>

        <?php endif; ?>

    </div>
</div>

<script>
    function copyPw() {
        const pw   = document.getElementById('tempPw').textContent.trim();
        const hint = document.getElementById('copyHint');
        navigator.clipboard.writeText(pw).then(() => {
            hint.textContent = '✅ Copied to clipboard!';
            hint.classList.add('copied');
            setTimeout(() => {
                hint.textContent = 'Click the password to copy it';
                hint.classList.remove('copied');
            }, 2500);
        });
    }
</script>

</body>
</html>