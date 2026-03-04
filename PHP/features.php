<?php
// Optional, but good practice for consistency with other pages
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Features | Smart Plate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap (for grid + spacing) -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        :root {
            --primary: #283618;
            --background: #FEFAE0;
            --card-bg: #ffffff;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--background);
            font-family: Arial, sans-serif;
            color: var(--primary);
        }

        /* NAVBAR – matches your Smart Plate style */
        .navbar-custom {
            background-color: var(--primary);
            width: 100%;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 40px;
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

        /* HERO SECTION */
        .hero-section {
            max-width: 1100px;
            margin: 40px auto 20px auto;
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            background-color: rgba(40, 54, 24, 0.1);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 12px;
        }

        .hero-section h1 {
            font-size: 2.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .hero-section p {
            font-size: 1rem;
            max-width: 700px;
            margin: 0 auto;
            color: #4b4b3a;
        }

        /* FEATURES GRID */
        .features-wrapper {
            max-width: 1100px;
            margin: 35px auto 60px auto;
        }

        .feature-card {
            background-color: var(--card-bg);
            border-radius: 14px;
            padding: 24px 22px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feature-tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background-color: rgba(40, 54, 24, 0.08);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 4px;
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .feature-text {
            font-size: 0.95rem;
            color: #4b4b3a;
        }

        .feature-list {
            padding-left: 1.1rem;
            margin-bottom: 0;
            font-size: 0.9rem;
            color: #4b4b3a;
        }

        .feature-list li {
            margin-bottom: 4px;
        }

        /* CTA SECTION */
        .cta-box {
            max-width: 900px;
            margin: 0 auto 80px auto;
            background-color: rgba(40, 54, 24, 0.1);
            border-radius: 18px;
            padding: 24px 24px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .cta-text {
            max-width: 520px;
        }

        .cta-text h3 {
            font-size: 1.3rem;
            margin-bottom: 6px;
        }

        .cta-text p {
            margin: 0;
            font-size: 0.95rem;
            color: #4b4b3a;
        }

        .btn-primary-custom {
            background-color: var(--primary);
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            background-color: #1f2a12;
            color: #fff;
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .nav-links {
                gap: 20px;
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<header class="navbar-custom">
    <div class="nav-container">
        <div class="logo">Smart Plate</div>
        <nav class="nav-links">
            <!-- nav bar -->
            <a href="/index.php">Home</a>
            <a href="features.php" class="active">Features</a>
            <a href="login.php">Sign In</a>
            <a href="../Pre-Meals.html">Pre-Meals</a>
        </nav>
    </div>
</header>

<!-- HERO -->
<section class="hero-section">
    <div class="hero-badge">Before you sign in</div>
    <h1>Smart Plate Features</h1>
    <p>
        Explore Smart Plate’s tools before creating an account. You can use the Nutrition Explorer,
        Ready-Made Meals, and Recipe Generator without signing in. Sign in later to save your plans
        and favorites.
    </p>
</section>

<!-- FEATURES GRID -->
<section class="features-wrapper">
    <div class="row g-4">

        <!-- Nutrition Explorer -->
        <div class="col-md-4">
            <div class="feature-card">
                <span class="feature-tag">Feature 01</span>
                <div class="feature-icon">🥦</div>
                <div class="feature-title">Nutrition Explorer</div>

                <p class="feature-text">
                    Search foods and see key nutrients like calories, protein, and more so
                    you can make informed choices.
                </p>

                <ul class="feature-list">
                    <li>Search by food name</li>
                    <li>View nutrition breakdown</li>
                    <li>Compare food options</li>
                </ul>

                <a href="../Nutrition Explore Page/nutrition-explorer.html" class="btn-primary-custom mt-2">
                    Open Nutrition Explorer
                </a>
            </div>
        </div>

        <!-- Ready-Made Meals -->
        <div class="col-md-4">
            <div class="feature-card">
                <span class="feature-tag">Feature 02</span>
                <div class="feature-icon">🍽️</div>
                <div class="feature-title">Ready-Made Meals</div>

                <p class="feature-text">
                    Browse curated meal ideas that are simple, senior-friendly, and easy
                    to plug into your weekly plan.
                </p>

                <ul class="feature-list">
                    <li>Pre-built meal suggestions</li>
                    <li>Balanced daily options</li>
                    <li>Great starting point for users</li>
                </ul>

                <a href="../Pre-Meals.html" class="btn-primary-custom mt-2">
                    Browse Ready Meals
                </a>
            </div>
        </div>

        <!-- Recipe Generator -->
        <div class="col-md-4">
            <div class="feature-card">
                <span class="feature-tag">Feature 03</span>
                <div class="feature-icon">📖</div>
                <div class="feature-title">Recipe Generator</div>

                <p class="feature-text">
                    Turn everyday ingredients into meal ideas using our recipe generator
                    powered by TheMealDB API.
                </p>

                <ul class="feature-list">
                    <li>Enter ingredients you have</li>
                    <li>Discover matching recipes</li>
                    <li>Explore cooking ideas</li>
                </ul>

                <a href="recipe_generator.php" class="btn-primary-custom mt-2">
                    Generate Recipes
                </a>
            </div>
        </div>

    </div>
</section>

<!-- CALL TO ACTION -->
<section class="cta-box">
    <div class="cta-text">
        <h3>Ready to build your own Smart Plate?</h3>
        <p>
            Sign in to unlock your personal dashboard, save favorite meals, and
            create meal plans tailored to your health goals.
        </p>
    </div>
    <a href="login.php" class="btn-primary-custom">
        Sign In to Start Planning
    </a>
</section>

</body>
</html>
