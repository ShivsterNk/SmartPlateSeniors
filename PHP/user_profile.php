<?php
include('../includes/header.php');
?>

<main>

<section id="profile-information">
    
    <div class="profile-layout">
        <!-- User Info -->
        <div class="info-section">
            <img src="avatar.jpg" alt="Profile Avatar" class="avatar">
            <p><strong>Username:</strong> Express</p>
            <p><strong>Password:</strong> ********</p>
            <p><strong>Change Password:</strong> Yes Or No<p>
            <p><strong>Email:</strong> email@email.com</p>
            <p><strong>Address:</strong> NY, USA</p>
            <p><strong>DOB:</strong> 01/01/2000</p>
        </div>

        <!-- Stats -->
        <div class="stats-section">
            <div class="card">
                <h4>Goals: </h4>
                <p>Calories: </p>
                <p>Fats: </p>
                <p>Proteins: </p>
                <p>Carbohydrates: </p>
            </div>
        </div>
    </div>
</section>

</main>

<?php
include('../includes/footer.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings | SmartPlate</title>
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

        /* ── Wrapper — wider on desktop ── */
        .profile-wrap {
            max-width: 960px;
            margin: 0 auto;
            padding: 36px 24px 60px;
        }

        /* ── Alerts ── */
        .alert-success-sp {
            background: #edf3eb; border: 1px solid #a7c957;
            color: #283618; border-radius: 10px;
            padding: 12px 16px; font-size: 0.88rem;
            font-weight: 600; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-error-sp {
            background: #fff0f0; border: 1px solid #fca5a5;
            color: #b91c1c; border-radius: 10px;
            padding: 12px 16px; font-size: 0.88rem;
            font-weight: 600; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }

        /* ── Avatar block ── */
        .avatar-block {
            display: flex;
            align-items: center;
            gap: 20px;
            background: white;
            border-radius: 16px;
            padding: 24px 28px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            margin-bottom: 24px;
        }
        .avatar-circle {
            width: 68px; height: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a2e10, #4a7c4a);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 700;
            color: white;
            flex-shrink: 0;
        }
        .avatar-info h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.35rem; margin: 0 0 4px;
            color: #1a2e10;
        }
        .avatar-info p { margin: 0; color: #7a8a7a; font-size: 0.85rem; }

        /* ── Side-by-side cards on desktop, stacked on mobile ── */
        .cards-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: start;
        }
        @media (max-width: 768px) {
            .cards-row {
                grid-template-columns: 1fr;
            }
            .profile-wrap {
                padding: 24px 16px 48px;
            }
            .avatar-block {
                flex-direction: column;
                text-align: center;
            }
            .avatar-info { text-align: center; }
        }

        /* ── Section cards ── */
        .section-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }
        .section-title {
            font-size: 1rem; font-weight: 700;
            color: #283618; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
            padding-bottom: 12px;
            border-bottom: 2px solid #edf3eb;
        }

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

        /* ── Save button ── */
        .btn-save {
            background: #283618; color: white;
            border: none; border-radius: 10px;
            padding: 11px 28px; font-weight: 700;
            font-size: 0.9rem; cursor: pointer;
            transition: background 0.2s;
            width: 100%; margin-top: 8px;
            font-family: 'DM Sans', sans-serif;
        }
        .btn-save:hover { background: #1f2a12; }

        /* ── Password show/hide toggle ── */
        .pw-wrapper { position: relative; }
        .pw-toggle {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; color: #7a8a7a;
            font-size: 1rem; padding: 0; line-height: 1;
        }
        .pw-toggle:hover { color: #283618; }

        /* ── Password strength bar ── */
        .strength-bar-wrap {
            height: 5px; background: #edf3eb;
            border-radius: 99px; margin-top: 8px;
            overflow: hidden;
        }
        .strength-bar {
            height: 100%; width: 0%;
            border-radius: 99px;
            transition: width 0.3s, background 0.3s;
        }
        .strength-label {
            font-size: 0.75rem; color: #7a8a7a;
            margin-top: 4px; min-height: 1em;
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="page-banner">
    <div class="container text-center">
        <div class="banner-icon">👤</div>
        <h1>Profile Settings</h1>
        <p>Manage your account information</p>
    </div>
</div>

<div class="profile-wrap">

    <?php if ($successMsg): ?>
        <div class="alert-success-sp">✅ <?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>
    <?php if ($errorMsg): ?>
        <div class="alert-error-sp">⚠️ <?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <!-- Avatar / Identity Block -->
    <div class="avatar-block">
        <div class="avatar-circle" id="avatarInitials">
            <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="avatar-info">
            <h2 id="displayName"><?= htmlspecialchars($user['name'] ?? '') ?></h2>
            <p id="displayEmail"><?= htmlspecialchars($user['email'] ?? '') ?></p>
        </div>
    </div>

    <!-- Side-by-side cards -->
    <div class="cards-row">

        <!-- ── Account Information ── -->
        <div class="section-card">
            <div class="section-title">✏️ Account Information</div>
            <form action="update_profile.php" method="POST" id="profileForm">
                <div class="mb-3">
                    <label class="form-label" for="name">Full Name</label>
                    <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                            required
                            autocomplete="name"
                    >
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email Address</label>
                    <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                            required
                            autocomplete="email"
                    >
                </div>
                <button type="submit" class="btn-save">Save Changes</button>
            </form>
        </div>

        <!-- ── Change Password ── -->
        <div class="section-card">
            <div class="section-title">🔒 Change Password</div>
            <form action="update_password.php" method="POST" id="passwordForm">

                <div class="mb-3">
                    <label class="form-label" for="current_password">Current Password</label>
                    <div class="pw-wrapper">
                        <input
                                type="password"
                                class="form-control"
                                id="current_password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                        >
                        <button type="button" class="pw-toggle" onclick="togglePw('current_password', this)">👁️</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="new_password">New Password</label>
                    <div class="pw-wrapper">
                        <input
                                type="password"
                                class="form-control"
                                id="new_password"
                                name="new_password"
                                required
                                autocomplete="new-password"
                                oninput="checkStrength(this.value)"
                        >
                        <button type="button" class="pw-toggle" onclick="togglePw('new_password', this)">👁️</button>
                    </div>
                    <div class="strength-bar-wrap">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-label" id="strengthLabel"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                    <div class="pw-wrapper">
                        <input
                                type="password"
                                class="form-control"
                                id="confirm_password"
                                name="confirm_password"
                                required
                                autocomplete="new-password"
                        >
                        <button type="button" class="pw-toggle" onclick="togglePw('confirm_password', this)">👁️</button>
                    </div>
                </div>

                <button type="submit" class="btn-save">Update Password</button>
            </form>
        </div>

    </div><!-- /.cards-row -->

</div><!-- /.profile-wrap -->

<script>
    // ── Toggle password visibility ──
    function togglePw(fieldId, btn) {
        const field = document.getElementById(fieldId);
        if (field.type === 'password') {
            field.type = 'text';
            btn.textContent = '🙈';
        } else {
            field.type = 'password';
            btn.textContent = '👁️';
        }
    }

    // ── Password strength meter ──
    function checkStrength(val) {
        const bar   = document.getElementById('strengthBar');
        const label = document.getElementById('strengthLabel');
        let score = 0;
        if (val.length >= 8)           score++;
        if (/[A-Z]/.test(val))         score++;
        if (/[0-9]/.test(val))         score++;
        if (/[^A-Za-z0-9]/.test(val))  score++;

        const levels = [
            { pct: '0%',   color: '#e63946', text: '' },
            { pct: '25%',  color: '#e63946', text: 'Weak' },
            { pct: '50%',  color: '#f4a261', text: 'Fair' },
            { pct: '75%',  color: '#a7c957', text: 'Good' },
            { pct: '100%', color: '#283618', text: 'Strong' },
        ];
        bar.style.width      = levels[score].pct;
        bar.style.background = levels[score].color;
        label.textContent    = levels[score].text;
        label.style.color    = levels[score].color;
    }

    // ── Live avatar + name update as user types ──
    document.getElementById('name')?.addEventListener('input', function () {
        const initial = this.value.trim().charAt(0).toUpperCase() || 'U';
        document.getElementById('avatarInitials').textContent = initial;
        document.getElementById('displayName').textContent    = this.value;
    });

    // ── Live email preview update ──
    document.getElementById('email')?.addEventListener('input', function () {
        document.getElementById('displayEmail').textContent = this.value;
    });

    // ── Client-side password match check before submit ──
    document.getElementById('passwordForm')?.addEventListener('submit', function (e) {
        const np = document.getElementById('new_password').value;
        const cp = document.getElementById('confirm_password').value;
        if (np !== cp) {
            e.preventDefault();
            alert('New passwords do not match. Please try again.');
        }
    });
</script>

</body>
</html>