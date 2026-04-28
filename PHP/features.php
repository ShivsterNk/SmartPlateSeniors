<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect logged-in users to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /SmartPlateSeniors/PHP/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Features | Smart Plate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap (for grid + spacing) -->
    <link rel="stylesheet"  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/SmartPlateSeniors/assets/spstyle.css">

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


        /* HERO SECTION */
        .hero-section {
            max-width: 1100px;
            margin: 50px auto 30px auto;
            text-align: center;
            padding: 30px 20px 0;
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 999px;
            background: linear-gradient(135deg, #283618, #4a7c4a);
            color: white; /* ✅ white text on dark background */
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 16px;
        }

        .hero-section h1 {
            font-size: 2.6rem;
            font-weight: 700;
            margin-bottom: 14px;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .hero-section p {
            font-size: 1.05rem;
            max-width: 620px;
            margin: 0 auto;
            color: #4b4b3a;
            line-height: 1.7;
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
            .hero-section,
            .features-wrapper,
            .cta-box {
                padding-left: 16px;
                padding-right: 16px;
                margin-left: 0;
                margin-right: 0;
                width: 100%;
                box-sizing: border-box;
            }

            .hero-section h1 {
                font-size: 1.6rem;
            }

            .hero-section p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<?php include('../includes/header.php'); ?>


<!-- HERO -->
<section class="hero-section">
    <div class="hero-badge">✨ No account needed</div>
    <h1>Everything You Need to Eat Smart</h1>
    <p>
        Start exploring SmartPlate's tools right now, no sign up required!
        When you're ready, create an account to unlock your personal meal plans,
        nutrition tracking, and more.
    </p>
</section>

<!-- FEATURES GRID -->
<section class="features-wrapper">
    <div class="row g-4">

        <!-- Nutrition Explorer -->
        <div class="col-md-4">
            <div class="feature-card">
                <span class="feature-tag">⚡ Try it now</span>
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

                <a href="../Nutrition Explore Page/nutrition-explorer.php" class="btn-primary-custom mt-2">
                    Open Nutrition Explorer
                </a>
            </div>
        </div>

        <!-- Ready-Made Meals -->
        <div class="col-md-4">
            <div class="feature-card">
                <span class="feature-tag">⚡ Try it now</span>
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

                <a href="readymeals.php" class="btn-primary-custom mt-2">
                    Browse Ready Meals
                </a>
            </div>
        </div>

        <!-- Recipe Generator -->
        <div class="col-md-4">
            <div class="feature-card">
                <span class="feature-tag">⚡ Try it now</span>
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
        <h3>🔒 Want the full SmartPlate experience?</h3>
        <p>
            Sign in to unlock AI-powered daily meal plans tailored to your dietary
            preferences, save favorite recipes, and track your nutrition — all in one place.
        </p>
    </div>
    <a href="login.php" class="btn-primary-custom">
        Sign In to Start Planning →
    </a>
</section>

</body>
</html>
