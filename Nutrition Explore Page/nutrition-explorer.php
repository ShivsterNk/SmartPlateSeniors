<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/api-keys.php';
$extraStyles = '
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="nutrition-explorer.css" />
';
include('../includes/header.php');
?>

    <!-- hero banner -->
    <div class="page-hero-banner">
        <div class="hero-inner">
            <h1 class="page-title">Nutrition Explorer</h1>
            <p class="page-subtitle">Search over 300,000 foods powered by the USDA FoodData Central database.</p>
        </div>
    </div>

    <main class="nutrition-main">
        <div class="search-card">
            <label class="search-label" for="query">Food Search</label>
            <div class="search-row">
                <input class="search-input" id="query" type="text"
                       placeholder="e.g. avocado, brown rice, cheddar cheese…"
                       autocomplete="off" />
                <button class="action-btn" id="searchBtn" onclick="doSearch()">Search</button>
            </div>
            <p class="status" id="status"></p>
            <div class="recent-wrap" id="recentWrap">
                <div class="recent-label">Recent searches</div>
                <div class="recent-pills" id="recentPills"></div>
            </div>
        </div>

        <div id="resultsSection"></div>
        <div id="detailPanel"></div>
    </main>

    <script>
        const API_KEY = '<?php echo FDC_API_KEY; ?>';
    </script>
    <script src="nutrition-explorer.js"></script>

<?php include('../includes/footer.php'); ?>