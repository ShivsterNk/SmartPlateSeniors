<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: /SmartPlateSeniors/PHP/dashboard.php');
    exit;
}

include('../includes/header.php');
?>


    <style>
        .hero-section {
            max-width: 1100px;
            margin: 0px auto 30px auto;
            text-align: center;
            padding: 30px 20px 0;
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--green-dark), #4a7c4a);
            color: white;
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
            color: var(--text-mid);
            line-height: 1.7;
        }

        .features-wrapper {
            max-width: 1100px;
            margin: 35px auto 60px auto;
            padding: 0 20px;
        }

        /* replaces bootstrap .row .col-md-4 */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background-color: white;
            border-radius: var(--radius);
            padding: 24px 22px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feature-tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background-color: var(--green-bg);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .feature-icon { font-size: 2rem; margin-bottom: 4px; }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .feature-text {
            font-size: 0.95rem;
            color: var(--text-mid);
        }

        .feature-list {
            padding-left: 1.1rem;
            margin-bottom: 0;
            font-size: 0.9rem;
            color: var(--text-mid);
        }

        .feature-list li { margin-bottom: 4px; }

        .cta-box {
            max-width: 900px;
            margin: 0 auto 80px auto;
            background-color: var(--green-bg);
            border-radius: 18px;
            padding: 24px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .cta-text { max-width: 520px; }
        .cta-text h3 { font-size: 1.3rem; margin-bottom: 6px; }
        .cta-text p {
            margin: 0;
            font-size: 0.95rem;
            color: var(--text-mid);
        }

        .btn-primary-custom {
            background-color: var(--green-dark);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            opacity: 0.85;
            color: white;
        }

        @media (max-width: 768px) {
            .features-grid { grid-template-columns: 1fr; }
            .hero-section h1 { font-size: 1.6rem; }
            .hero-section p { font-size: 0.9rem; }
        }
    </style>

    <main>

        <section class="hero-section">
            <div class="hero-badge">✨ No account needed</div>
            <h1>Everything You Need to Eat Smart</h1>
            <p>
                Start exploring SmartPlate's tools right now, no sign up required!
                When you're ready, create an account to unlock your personal meal plans,
                nutrition tracking, and more.
            </p>
        </section>

        <!-- features grid -->
        <section class="features-wrapper">
            <div class="features-grid">

                <!-- nutrition explorer -->
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

                <!-- ready-made meals -->
                <div class="feature-card">
                    <span class="feature-tag">⚡ Try it now</span>
                    <div class="feature-icon">🍽️</div>
                    <div class="feature-title">Smart Meals</div>

                    <p class="feature-text">
                        Browse curated meal ideas that are simple, senior-friendly, and easy
                        to plug into your weekly plan.
                    </p>

                    <ul class="feature-list">
                        <li>Pre-built meal suggestions</li>
                        <li>Balanced daily options</li>
                        <li>Great starting point for users</li>
                    </ul>

                    <a href="smartmeals.php" class="btn-primary-custom mt-2">
                        Browse Smart Meals
                    </a>
                </div>

                <!-- recipe generator -->
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
        </section>

        <!-- cta -->
        <section class="cta-box">
            <div class="cta-text">
                <h3>🔒 Want the full SmartPlate experience?</h3>
                <p>
                    Sign in to unlock AI-powered daily meal plans tailored to your dietary
                    preferences, save favorite recipes, and track your nutrition. All in one place.
                </p>
            </div>
            <a href="login.php" class="btn-primary-custom">
                Sign In to Start Planning →
            </a>
        </section>

    </main>

<?php include('../includes/footer.php'); ?>