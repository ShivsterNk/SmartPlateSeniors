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
            <img src= "../js/New Smartplate logo.png" alt="SmartPlate Logo" class="logo-img">
        </div>

        <div class="nav-links">

            <?php if(!$isLoggedIn): ?>
                <!-- PUBLIC NAV -->
                <a href="/SmartPlateSeniors/PHP/index.php">Home</a>
                <a href="/SmartPlateSeniors/PHP/features.php">Features</a>
                <a href="/SmartPlateSeniors/PHP/login.php">Sign In</a>

            <?php else: ?>
                <!-- APP NAV -->
                <a href="/SmartPlateSeniors/PHP/dashboard.php">Dashboard</a>
                <a href="/SmartPlateSeniors/Pages/platebot.php">Platebot</a>

                <!-- 🔥 MORE DROPDOWN -->
                <div class="nav-dropdown">
                    <button class="dropdown-btn">More ▾</button>

                    <div class="dropdown-menu">
                        <a href="/SmartPlateSeniors/Nutrition Explore Page/nutrition-explorer.php">Explore</a>
                        <a href="/SmartPlateSeniors/PHP/recipe_generator.php">Recipe Generator</a>
                        <a href="/SmartPlateSeniors/Pages/shopping_list.php">Shopping List</a>
                        <a href="/SmartPlateSeniors/PHP/favorites.php">Favorites</a>
                    </div>
                </div>
                <!-- ✅ ADD THIS -->
                <a href="/SmartPlateSeniors/PHP/logout.php" class="nav-logout">Logout</a>

            <?php endif; ?>

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