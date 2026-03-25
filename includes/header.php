<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Plate</title>
<link rel="stylesheet" href="/SmartPlateSeniors/assets/spstyle.css">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
         <div class="logo">
            <img src="../js/New Smartplate logo.png" alt="SmartPlate Logo" class="logo-img">
        </div>

        <div class="nav-right" id="navRight">
            <div class="nav-links">
                <a href="/SmartPlateSeniors/PHP/index.php">Home</a>
                <a href="/SmartPlateSeniors/PHP/features.php">Features</a>
                <?php if(!$isLoggedIn): ?>
                    <a href="/SmartPlateSeniors/PHP/login.php">Sign In</a>
                <?php endif; ?>
            </div>

            <?php if($isLoggedIn): ?>
                <div class="nav-profile-btn" id="navProfileBtn">
                    <div class="avatar"><?php echo strtoupper(substr($username,0,1)); ?></div>
                    <span class="nav-username"><?php echo htmlspecialchars($username); ?></span>
                    <span class="nav-chevron">▾</span>
                </div>
                <div class="profile-dropdown" id="profileDropdown">
                    <a href="dashboard.php" class="pd-item">Dashboard</a>
                    <a href="logout.php" class="pd-item danger">Logout</a>
                </div>
            <?php endif; ?>
        </div>

        <button class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </button>
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