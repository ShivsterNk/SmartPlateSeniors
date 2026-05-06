<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$username   = $_SESSION['user_name'] ?? '';
$initial    = strtoupper(substr($username, 0, 1) ?: 'U');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Plate</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/SmartPlateSeniors/assets/spstyle.css">
    <?php if (!empty($extraStyles)) echo $extraStyles; ?>

    <style>
        .navbar{
            padding-bottom: 0px !important;
        }
        /* profile avatar button */
        .nav-profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 999px;
            padding: 5px 12px 5px 5px;
            color: white;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background 0.2s;
        }
        .nav-profile-btn:hover {
            background: rgba(255,255,255,0.22);
        }
        .nav-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a7c957, #4a7c4a);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; font-weight: 700;
            color: #1a2e10;
            flex-shrink: 0;
        }

        /* align profile dropdown right */
        .dropdown-menu-right {
            right: 0;
            left: auto;
        }
        .dropdown-divider {
            border-top: 1px solid #edf3eb;
            margin: 4px 0;
        }
    </style>
</head>

<body>
<nav class="navbar">
    <div class="nav-container">
        <div class="logo">
            <img src="/SmartPlateSeniors/js/New Smartplate logo.png" alt="SmartPlate Logo" class="logo-img">
        </div>

        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="nav-right" id="navRight">
            <ul class="nav-links">
                <?php if (!$isLoggedIn): ?>
                    <li><a href="/SmartPlateSeniors/PHP/index.php">Home</a></li>
                    <li><a href="/SmartPlateSeniors/PHP/features.php">Features</a></li>
                    <li><a href="/SmartPlateSeniors/PHP/login.php">Sign In</a></li>
                <?php else: ?>
                    <li><a href="/SmartPlateSeniors/PHP/dashboard.php">Dashboard</a></li>

                    <!-- more dropdown -->
                    <li class="nav-dropdown">
                        <button class="dropdown-btn">More
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div class="dropdown-menu">
                            <a href="/SmartPlateSeniors/Nutrition Explore Page/nutrition-explorer.php">
                                <span>🔍</span> Explore
                            </a>
                            <a href="/SmartPlateSeniors/PHP/recipe_generator.php">
                                <span>📖</span> Recipe Generator
                            </a>
                            <a href="/SmartPlateSeniors/PHP/shopping-list.php">
                                <span>🛒</span> Shopping List
                            </a>
                            <a href="/SmartPlateSeniors/PHP/favorites.php">
                                <span>❤️</span> Favorites
                            </a>
                        </div>
                    </li>

                    <!-- profile dropdown -->
                    <li><a href="/SmartPlateSeniors/PHP/logout.php">Sign Out</a></li>

                <?php endif;?>
            </ul>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        // hamburger
        const hamburger = document.getElementById("hamburger");
        const navRight  = document.getElementById("navRight");
        if (hamburger && navRight) {
            hamburger.addEventListener("click", () => {
                hamburger.classList.toggle("active");
                navRight.classList.toggle("active");
            });
        }

        // profile dropdown
        const navProfileBtn    = document.getElementById("navProfileBtn");
        const profileDropdown  = document.getElementById("profileDropdown");

        if (navProfileBtn && profileDropdown) {
            navProfileBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle("open");
            });

            document.addEventListener("click", () => {
                profileDropdown.classList.remove("open");
            });

            document.addEventListener("keydown", (e) => {
                if (e.key === 'Escape') profileDropdown.classList.remove("open");
            });
        }

        // more dropdowns
        document.querySelectorAll('.nav-dropdown').forEach(item => {
            const btn  = item.querySelector('.dropdown-btn');
            const menu = item.querySelector('.dropdown-menu');
            if (!btn || !menu) return;
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('open');
                });
                menu.classList.toggle('open');
            });
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('open'));
        });

    });
</script>