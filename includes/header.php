<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['user_name'] ?? '';

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
                            <a href="/SmartPlateSeniors/Pages/shopping_list.php">
                                <span>🛒</span> Shopping List
                            </a>
                            <a href="/SmartPlateSeniors/PHP/favorites.php">
                                <span>❤️</span> Favorites
                            </a>
                        </div>
                    </li>
                    <li>
                        <a href="/SmartPlateSeniors/PHP/logout.php" class="nav-logout">
                            Sign Out
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


<script>
    document.addEventListener("DOMContentLoaded", () => {

        const hamburger = document.getElementById("hamburger");
        const navRight = document.getElementById("navRight");

        if (hamburger && navRight) {
            hamburger.addEventListener("click", () => {
                hamburger.classList.toggle("active");
                navRight.classList.toggle("active");
            });
        }

        const navProfileBtn = document.getElementById("navProfileBtn");
        const profileDropdown = document.getElementById("profileDropdown");

        if (navProfileBtn && profileDropdown) {
            let profileOpen = false;

            navProfileBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                profileOpen = !profileOpen;
                profileDropdown.classList.toggle("open", profileOpen);
            });

            document.addEventListener("click", () => {
                profileDropdown.classList.remove("open");
            });

            document.addEventListener("keydown", (e) => {
                if (e.key === 'Escape') {
                    profileDropdown.classList.remove("open");
                }
            });
        }

    });
</script>