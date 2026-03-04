<?php
session_start();
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

        /* NAVBAR BACKGROUND */
        .navbar-custom {
            background-color: #283618;
            width: 100%;
        }

        /* CONTAINER THAT HOLDS LOGO + LINKS */
        .nav-container {
            width: 100%;
            max-width: 1350px;   /* Controls how wide everything spreads */
            margin: 0 auto;
            padding: 18px 25px;   /* Padding matches your screenshot */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* LOGO */
        .logo {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* NAV LINKS */
        .nav-links {
            display: flex;
            gap: 40px;  /* EXACT spacing between Home, Plan, Sign In, Pre-Meals */
        }

        .nav-links a {
            color: white;
            font-weight: 600;
            text-decoration: none;
            font-size: 1rem;
        }

        .nav-links a:hover,
        .nav-links a.active {
            text-decoration: underline;
        }


        /* BUTTON STYLE */
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
    </style>
</head>

<body>

<!-- NAVBAR -->
<header class="navbar-custom">
    <div class="container d-flex justify-content-between align-items-center py-3">

        <div class="logo">
            Smart Plate
        </div>

        <nav class="nav-links">
            <a href="Home.php">Home</a>
            <a href="Plan.php">Plan</a>
            <a href="login.php">Sign In</a>
            <a href="Pre-Meals.php">Pre-Meals</a>
        </nav>

    </div>
</header>

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

<!-- axios library (must be before your JS file) -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- your JS file (path from recipe_generator.php to js folder) -->
<script src="../js/recipe_generator.js"></script>

</body>
</html>
</body>
</html>