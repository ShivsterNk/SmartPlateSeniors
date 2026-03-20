<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Generator | Smart Plate</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #FEFAE0;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar-custom { background-color: #283618; width: 100%; }

        .logo {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .nav-links { display: flex; gap: 40px; }
        .nav-links a {
            color: white;
            font-weight: 600;
            text-decoration: none;
            font-size: 1rem;
        }
        .nav-links a:hover, .nav-links a.active { text-decoration: underline; }

        .btn-primary {
            background-color: #283618 !important;
            border-color: #283618 !important;
        }
        .btn-primary:hover {
            background-color: #1f2a12 !important;
            border-color: #1f2a12 !important;
        }
        .btn-outline-primary {
            color: #283618 !important;
            border-color: #283618 !important;
        }
        .btn-outline-primary:hover {
            background-color: #283618 !important;
            color: #fff !important;
        }

        /* ── Favorite button ── */
        .btn-fav {
            background: none;
            border: none;
            font-size: 1.4rem;
            cursor: pointer;
            color: #ccc;
            transition: color 0.2s, transform 0.15s;
            padding: 0;
            line-height: 1;
        }
        .btn-fav:hover { transform: scale(1.2); }
        .btn-fav.saved { color: #e63946; }

        /* ── Login prompt modal ── */
        .login-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .login-modal-overlay.show { display: flex; }
        .login-modal-box {
            background: #fff;
            border-radius: 14px;
            padding: 36px 32px;
            max-width: 380px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }
        .login-modal-box .modal-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
        }
        .login-modal-box h5 {
            color: #283618;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .login-modal-box p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .modal-btn-login {
            display: block;
            width: 100%;
            background-color: #283618;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 10px;
            transition: background 0.2s;
        }
        .modal-btn-login:hover { background-color: #1f2a12; color: #fff; }
        .modal-btn-cancel {
            background: none;
            border: none;
            color: #999;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: underline;
        }
        .modal-btn-cancel:hover { color: #555; }
    </style>
</head>

<body>

<!-- NAVBAR -->
<header class="navbar-custom">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <div class="logo">Smart Plate</div>
        <nav class="nav-links">
            <a href="/PHP/index.php">Home</a>
            <a href="features.php" class="active">Features</a>
            <a href="login.php">Sign In</a>
            <a href="readymeals.php">Pre-Meals</a>
        </nav>
    </div>
</header>

<!-- LOGIN PROMPT MODAL -->
<div class="login-modal-overlay" id="loginModal">
    <div class="login-modal-box">
        <div class="modal-icon">🔒</div>
        <h5>Sign in to Save Recipes</h5>
        <p>Create a free account or sign in to save your favorite recipes and access them anytime.</p>
        <a href="login.php" class="modal-btn-login">Sign In</a>
        <button class="modal-btn-cancel" id="modalCancel">Maybe later</button>
    </div>
</div>

<!-- PAGE CONTENT -->
<div class="container my-5">
    <h1 class="text-center mb-3">Recipe Generator</h1>
    <p class="text-center text-muted mb-4">
        Enter a main ingredient and explore delicious recipes.
    </p>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form id="recipe-form">
                <div class="mb-3">
                    <label for="ingredient" class="form-label">Main Ingredient</label>
                    <input type="text" class="form-control" id="ingredient"
                           placeholder="e.g. beef, chicken, salmon" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    Generate Recipes
                </button>
            </form>
        </div>
    </div>

    <div id="loading" class="text-center mb-3" style="display:none;">
        <div class="spinner-border"></div>
        <p class="mt-2">Searching recipes...</p>
    </div>

    <div id="error" class="alert alert-danger d-none"></div>
    <div id="results" class="row g-3"></div>
</div>

<!-- Pass session status to JS -->
<script>
    const IS_LOGGED_IN = <?= $isLoggedIn ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="../js/recipe_generator.js"></script>

</body>
</html>