<?php
//index.php
include('../includes/header.php');
?>

    <main>
        <section class="hero-bg">
            <div class="hero-overlay">
                <h1>Eat Smarter,<br>Live Better.</h1>
                <p>Plan nutritious meals, discover recipes, and track your nutrition — all in one place.</p>

                <div class="hero-buttons">
                    <a href="about.php" class="homebtn">Learn More</a>
                    <a href="readymeals.php" class="homebtn">Check Our Ready-To-Eat Meals</a>
                </div>
            </div>
        </section>

        <!-- ── HOW IT WORKS ── -->
        <section class="how-it-works">
            <h2 class="section-title">How It Works</h2>
            <p class="section-sub">Getting started with Smart Plate is simple</p>
            <div class="steps-row">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <div class="step-icon">&#128100;</div>
                    <h3>Create an Account</h3>
                    <p>Sign up for free and set up your personal profile in minutes.</p>
                </div>
                <div class="step-divider">&#8594;</div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <div class="step-icon">&#9989;</div>
                    <h3>Set Your Preferences</h3>
                    <p>Tell us your dietary needs, goals, and how many meals you want per day.</p>
                </div>
                <div class="step-divider">&#8594;</div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <div class="step-icon">&#127859;</div>
                    <h3>Get Your Meal Plan</h3>
                    <p>Receive personalized meal plans and recipes tailored just for you.</p>
                </div>
            </div>
        </section>

        <!-- ── FEATURES CTA ── -->
        <section class="features-cta">
            <div class="features-cta-inner">
                <h2>Discover What Smart Plate Can Do</h2>
                <p>From nutrition tracking to AI-powered meal plans — explore everything we offer.</p>
                <a href="features.php" class="cta-btn">Explore Our Features &#8594;</a>
            </div>
        </section>

        <!-- ── SIGN UP CTA ── -->
        <section class="signup-cta">
            <h2>Ready to eat smarter?</h2>
            <p>Join Smart Plate today and take control of your nutrition.</p>
            <a href="signup.php" class="signup-btn">Get Started — It's Free</a>
        </section>

        <script>
            const hamburger = document.getElementById("hamburger");
            const navMenu = document.getElementById("navMenu");

            hamburger.addEventListener("click", () => {
                navMenu.classList.toggle("show");
                hamburger.textContent = navMenu.classList.contains("show") ? "x" : "☰";
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    navMenu.classList.remove('show');
                    hamburger.textContent = '☰';
                }
            });
        </script>
    </main>

<?php
include('../includes/footer.php');
?>