<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Generator | Smart Plate</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        * { box-sizing: border-box; }
        body { background-color: #FEFAE0; margin: 0; font-family: Arial, sans-serif; }

        /* NAVBAR */
        .navbar-custom { background-color: #283618; width: 100%; }
        .logo { color: white; font-size: 1.8rem; font-weight: 700; letter-spacing: 0.5px; }
        .nav-links { display: flex; gap: 40px; }
        .nav-links a { color: white; font-weight: 600; text-decoration: none; font-size: 1rem; }
        .nav-links a:hover { text-decoration: underline; }

        /* HERO */
        .hero {
            background: #283618;
            padding: 60px 0 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80') center/cover no-repeat;
            opacity: 0.15;
        }
        .hero-content { position: relative; z-index: 1; }
        .hero h1 { color: white; font-size: 2.4rem; font-weight: 700; margin-bottom: 10px; }
        .hero p { color: rgba(255,255,255,0.85); font-size: 1.05rem; margin-bottom: 32px; }

        /* SEARCH */
        .search-card {
            background: white; border-radius: 16px;
            padding: 24px 28px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            max-width: 600px; margin: 0 auto;
        }
        .search-input-group { display: flex; gap: 10px; }
        .search-input-group input {
            flex: 1; border: 2px solid #d8e8d4; border-radius: 10px;
            padding: 12px 16px; font-size: 0.95rem; color: #283618; outline: none;
            transition: border-color 0.2s;
        }
        .search-input-group input:focus { border-color: #283618; }
        .search-input-group input::placeholder { color: #aaa; }
        .btn-search {
            background: #283618; color: white; border: none;
            border-radius: 10px; padding: 12px 24px;
            font-size: 0.95rem; font-weight: 700; cursor: pointer;
            white-space: nowrap; transition: background 0.2s;
        }
        .btn-search:hover { background: #1f2a12; }

        /* RECENT SEARCHES */
        .recent-searches { margin-top: 14px; display: none; }
        .recent-label {
            font-size: 0.75rem; color: #888; margin-bottom: 8px;
            font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
        }
        .recent-pills { display: flex; flex-wrap: wrap; gap: 6px; }
        .recent-pill {
            background: #edf3eb; color: #283618;
            border: 1px solid #d8e8d4; border-radius: 20px;
            padding: 4px 12px; font-size: 0.8rem; font-weight: 600;
            cursor: pointer; transition: all 0.15s;
        }
        .recent-pill:hover { background: #283618; color: white; border-color: #283618; }

        /* MAIN */
        .main-content { padding: 40px 0 60px; }

        /* CATEGORIES */
        .section-label {
            font-size: 0.78rem; font-weight: 700; color: #888;
            text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 12px;
        }
        .category-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 32px; }
        .cat-pill {
            background: white; color: #283618; border: 2px solid #d8e8d4;
            border-radius: 24px; padding: 7px 16px; font-size: 0.85rem;
            font-weight: 600; cursor: pointer; transition: all 0.18s;
            display: flex; align-items: center; gap: 6px;
        }
        .cat-pill img { width: 22px; height: 22px; border-radius: 50%; object-fit: cover; }
        .cat-pill:hover { border-color: #283618; background: #edf3eb; }
        .cat-pill.active { background: #283618; color: white; border-color: #283618; }
        .cat-pill-skeleton {
            background: #e0e0e0; border-radius: 24px;
            height: 36px; width: 90px;
            animation: shimmer 1.2s infinite;
        }

        /* LOADING */
        .loading-wrap { text-align: center; padding: 60px 0; display: none; }
        .spinner-border { color: #283618; width: 2.5rem; height: 2.5rem; }
        .loading-wrap p { color: #888; margin-top: 12px; font-size: 0.9rem; }

        /* RESULTS */
        #results { display: none; }

        /* CARDS */
        .recipe-card {
            background: white; border-radius: 14px; overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
            height: 100%; opacity: 0; transform: translateY(16px);
            animation: fadeUp 0.35s ease forwards;
        }
        .recipe-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(40,54,24,0.15);
        }
        .card-img-wrap { overflow: hidden; }
        .recipe-card img {
            width: 100%; aspect-ratio: 4/3; object-fit: cover;
            transition: transform 0.3s ease;
        }
        .recipe-card:hover img { transform: scale(1.04); }
        .card-body-custom {
            padding: 16px; display: flex;
            flex-direction: column; gap: 10px;
        }
        .card-title-row {
            display: flex; justify-content: space-between;
            align-items: flex-start; gap: 8px;
        }
        .card-meal-title {
            font-size: 0.95rem; font-weight: 700;
            color: #283618; line-height: 1.3; flex: 1;
        }

        /* HEART */
        .btn-fav {
            background: none; border: none; font-size: 1.3rem;
            cursor: pointer; color: #ddd;
            transition: color 0.2s, transform 0.15s;
            padding: 0; line-height: 1; flex-shrink: 0;
        }
        .btn-fav:hover { color: #e63946; transform: scale(1.2); }
        .btn-fav.saved { color: #e63946; }

        /* VIEW BUTTON */
        .btn-view-recipe {
            display: block; background: #edf3eb; color: #283618;
            border: 2px solid #d8e8d4; border-radius: 8px;
            padding: 8px 14px; font-size: 0.85rem; font-weight: 700;
            text-decoration: none; text-align: center; transition: all 0.18s;
        }
        .btn-view-recipe:hover { background: #283618; color: white; border-color: #283618; }

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 60px 20px; display: none; }
        .empty-icon { font-size: 4rem; margin-bottom: 16px; }
        .empty-state h4 { color: #283618; font-weight: 700; margin-bottom: 8px; }
        .empty-state p { color: #888; font-size: 0.9rem; }

        /* ERROR */
        .alert-error {
            background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5;
            border-radius: 10px; padding: 14px 20px;
            font-size: 0.88rem; display: none; margin-bottom: 20px;
        }

        /* LOGIN MODAL */
        .login-modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 9999;
            align-items: center; justify-content: center;
        }
        .login-modal-overlay.show { display: flex; }
        .login-modal-box {
            background: white; border-radius: 16px; padding: 40px 32px;
            max-width: 380px; width: 90%; text-align: center;
            box-shadow: 0 12px 40px rgba(0,0,0,0.2);
        }
        .modal-icon { font-size: 2.8rem; margin-bottom: 14px; }
        .login-modal-box h5 { color: #283618; font-weight: 700; font-size: 1.1rem; margin-bottom: 8px; }
        .login-modal-box p { color: #666; font-size: 0.88rem; margin-bottom: 22px; }
        .modal-btn-login {
            display: block; width: 100%; background: #283618; color: white;
            border: none; border-radius: 10px; padding: 12px;
            font-size: 0.95rem; font-weight: 700; text-decoration: none;
            margin-bottom: 10px; transition: background 0.2s;
        }
        .modal-btn-login:hover { background: #1f2a12; color: white; }
        .modal-btn-cancel {
            background: none; border: none; color: #999;
            font-size: 0.85rem; cursor: pointer; text-decoration: underline;
        }

        /* ANIMATIONS */
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes shimmer { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
    </style>
</head>
<body>

<!-- NAVBAR -->
<header class="navbar-custom">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <div class="logo">Smart Plate</div>
        <nav class="nav-links">
            <a href="/PHP/index.php">Home</a>
            <a href="features.php">Features</a>
            <a href="login.php">Sign In</a>
            <a href="readymeals.php">Pre-Meals</a>
        </nav>
    </div>
</header>

<!-- LOGIN MODAL -->
<div class="login-modal-overlay" id="loginModal">
    <div class="login-modal-box">
        <div class="modal-icon">&#128274;</div>
        <h5>Sign in to Save Recipes</h5>
        <p>Create a free account or sign in to save your favorite recipes and access them anytime.</p>
        <a href="login.php" class="modal-btn-login">Sign In</a>
        <button class="modal-btn-cancel" id="modalCancel">Maybe later</button>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-content">
        <h1>&#127859; Recipe Generator</h1>
        <p>Enter an ingredient or pick a category to discover delicious recipes</p>
        <div class="search-card">
            <div class="search-input-group">
                <input type="text" id="ingredient" placeholder="e.g. chicken, salmon, beef...">
                <button class="btn-search" id="searchBtn">Find Recipes</button>
            </div>
            <div class="recent-searches" id="recentSearches">
                <div class="recent-label">Recent searches</div>
                <div class="recent-pills" id="recentPills"></div>
            </div>
        </div>
    </div>
</div>

<!-- MAIN -->
<div class="main-content">
    <div class="container">

        <div class="section-label">Browse by category</div>
        <div class="category-pills" id="categoryPills">
            <div class="cat-pill-skeleton"></div>
            <div class="cat-pill-skeleton"></div>
            <div class="cat-pill-skeleton"></div>
            <div class="cat-pill-skeleton"></div>
            <div class="cat-pill-skeleton"></div>
            <div class="cat-pill-skeleton"></div>
        </div>

        <div class="alert-error" id="error"></div>

        <div class="loading-wrap" id="loading">
            <div class="spinner-border" role="status"></div>
            <p>Finding delicious recipes...</p>
        </div>

        <div class="empty-state" id="emptyState">
            <div class="empty-icon">&#127859;</div>
            <h4>No recipes found</h4>
            <p>Try a different ingredient or pick a category above.</p>
        </div>

        <div id="results" class="row g-4"></div>

    </div>
</div>

<script>
    const IS_LOGGED_IN = <?= $isLoggedIn ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="../js/recipe_generator.js"></script>

</body>
</html>