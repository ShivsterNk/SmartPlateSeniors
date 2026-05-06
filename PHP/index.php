<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: /SmartPlateSeniors/PHP/dashboard.php');
    exit;
}

include('../includes/header.php');
?>

    <style>
        body { background: cornsilk; overflow-x: hidden; }


        main section { margin: 0 !important; }

        /* hero */
        .hero {
            position: relative;
            height: 90vh;
            background: url('/SmartPlateSeniors/assets/Images/healthyplatter.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;

        }

        .hero::after {
            content: '';
            position: absolute; inset: 0;
            background: rgba(26, 46, 16, 0.88);
            z-index: 0;
        }

        .hero-content {
            position: relative; z-index: 2;
            max-width: 700px; margin: 0 auto;
            padding: 80px 32px;
            width: 100%;
            text-align: center;
        }

        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(167,201,87,0.2);
            border: 1px solid rgba(167,201,87,0.45);
            border-radius: 999px;
            padding: 6px 18px;
            font-size: 0.78rem; font-weight: 700;
            color: var(--green-light); letter-spacing: 0.1em;
            text-transform: uppercase; margin-bottom: 28px;
            opacity: 0; animation: slideUp 0.6s 0.2s ease forwards;
        }

        .hero-title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(3rem, 7.5vw, 6rem);
            color: #fff; line-height: 1.02;
            margin: 0 0 24px;
            max-width: 640px;
            opacity: 0; animation: slideUp 0.7s 0.35s ease forwards;
        }
        .hero-title span { color: var(--green-light); font-style: italic; }

        .hero-desc {
            font-size: clamp(1rem, 1.6vw, 1.2rem);
            color: rgba(255,255,255,0.72);
            line-height: 1.75; max-width: 480px;
            margin: 0 auto 40px;
            opacity: 0; animation: slideUp 0.7s 0.5s ease forwards;
        }

        .hero-actions {
            display: flex; gap: 14px; flex-wrap: wrap;
            justify-content: center;
            opacity: 0; animation: slideUp 0.7s 0.65s ease forwards;
        }

        .btn-primary {
            background: var(--green-light); color: var(--green-dark);
            font-weight: 700; font-size: 0.95rem;
            padding: 15px 34px; border-radius: 999px;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-block;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 36px rgba(167,201,87,0.45);
            color: var(--green-dark);
        }
        .btn-ghost {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.35);
            color: #fff; font-weight: 600; font-size: 0.95rem;
            padding: 15px 34px; border-radius: 999px;
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
            display: inline-block;
        }
        .btn-ghost:hover {
            border-color: #fff;
            background: rgba(255,255,255,0.08);
            color: #fff;
        }



        /* scroll cue */
        .scroll-cue {
            position: absolute; bottom: 32px; left: 50%;
            transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center;
            z-index: 2;
            opacity: 0; animation: fadeIn 1s 1.2s ease forwards;
            cursor: pointer;
        }
        .scroll-chevron {
            width: 28px; height: 28px;
            border-right: 3px solid rgba(255,255,255,0.5);
            border-bottom: 3px solid rgba(255,255,255,0.5);
            transform: rotate(45deg);
            animation: bounceChev 1.5s ease infinite;
            border-radius: 2px;
        }
        @keyframes bounceChev {
            0%,100% { transform: rotate(45deg) translateY(0); opacity: 0.5; }
            50%      { transform: rotate(45deg) translateY(6px); opacity: 1; }
        }

        /* shared */
        .sp { padding: 96px 24px; }
        .sp-inner { max-width: 1100px; margin: 0 auto; padding: 0 32px; }
        .sp-label {
            font-size: 0.73rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--text-mid); margin-bottom: 10px;
            text-align: center;
        }
        .sp-heading {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--green-dark); line-height: 1.15; margin-bottom: 14px;
            text-align: center;
        }
        .sp-sub {
            font-size: 1rem; color: var(--text-light);
            line-height: 1.75; max-width: 520px;
            text-align: center;
            margin-left: auto; margin-right: auto;
        }

        /* bento grid */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 56px;
        }
        .bento-card {
            background: #fff;
            border-radius: 20px;
            padding: 32px 28px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
            position: relative; overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: default;
        }
        .bento-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.11);
        }
        .bento-card.wide { grid-column: span 2; }
        .bento-card.tall { grid-row: span 2; }

        .bento-card-tag {
            display: inline-block;
            background: cornsilk;
            color: var(--text-mid); font-size: 0.72rem;
            font-weight: 700; letter-spacing: 0.07em;
            text-transform: uppercase; border-radius: 6px;
            padding: 4px 10px; margin-bottom: 16px;
        }
        .bento-icon { font-size: 2.4rem; margin-bottom: 14px; display: block; }
        .bento-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.35rem; color: var(--green-dark);
            margin-bottom: 10px; line-height: 1.2;
        }
        .bento-desc { font-size: 0.9rem; color: var(--text-light); line-height: 1.7; }


        .bento-card::after {
            content: '';
            position: absolute;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: var(--green-light);
            opacity: 0.06;
            bottom: -30px; right: -30px;
            transition: opacity 0.3s, transform 0.3s;
        }
        .bento-card:hover::after { opacity: 0.12; transform: scale(1.3); }


        .bento-card.platebot {
            background: linear-gradient(145deg, var(--green-dark), #2d4a1a);
            color: white;
        }
        .bento-card.platebot .bento-title { color: white; }
        .bento-card.platebot .bento-desc  { color: rgba(255,255,255,0.65); }
        .bento-card.platebot .bento-card-tag { background: rgba(167,201,87,0.2); color: var(--green-light); }
        .bento-card.platebot::after { background: var(--green-light); opacity: 0.08; }

        .platebot-chat {
            margin-top: 20px;
            display: flex; flex-direction: column; gap: 10px;
        }
        .pb-bubble {
            background: rgba(255,255,255,0.1);
            border-radius: 12px 12px 12px 3px;
            padding: 10px 14px;
            font-size: 0.82rem; color: rgba(255,255,255,0.8);
            line-height: 1.5; max-width: 90%;
        }
        .pb-bubble.user {
            background: rgba(167,201,87,0.2);
            border-radius: 12px 12px 3px 12px;
            align-self: flex-end; color: var(--green-light);
        }

        /* how it works */
        .how-section { background: var(--green-dark); }
        .how-section .sp-label { color: var(--green-light); }
        .how-section .sp-heading { color: #fff; }
        .how-section .sp-sub { color: rgba(255,255,255,0.55); }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px;
            margin-top: 56px;
            position: relative;
        }


        .steps-grid::before {
            content: '';
            position: absolute;
            top: 52px; left: calc(100%/6);
            width: calc(100% - 100%/3);
            height: 2px;
            background: linear-gradient(to right, var(--green-light), var(--text-mid));
            opacity: 0.3;
            z-index: 0;
        }

        .step-item {
            text-align: center; padding: 40px 28px;
            position: relative; z-index: 1;
            transition: transform 0.3s;
        }
        .step-item:hover { transform: translateY(-8px); }

        .step-num-badge {
            width: 52px; height: 52px;
            border-radius: 50%;
            background: var(--green-light);
            color: var(--green-dark);
            font-family: 'DM Serif Display', serif;
            font-size: 1.3rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 0 0 8px rgba(167,201,87,0.15);
            transition: box-shadow 0.3s;
        }
        .step-item:hover .step-num-badge {
            box-shadow: 0 0 0 14px rgba(167,201,87,0.2);
        }
        .step-emoji { font-size: 2rem; margin-bottom: 14px; display: block; }
        .step-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.15rem; color: #fff; margin-bottom: 10px;
        }
        .step-desc { font-size: 0.88rem; color: rgba(255,255,255,0.55); line-height: 1.7; }

        /* quote band */
        .quote-band {
            background: #FEFAE0;
            padding: 72px 24px;
            text-align: center;
            overflow: hidden;
            position: relative;
        }
        .quote-band::before {
            content: '"';
            font-family: 'DM Serif Display', serif;
            font-size: 28rem; line-height: 0.8;
            color: rgba(40,54,24,0.07);
            position: absolute; top: -60px; left: -40px;
            pointer-events: none; user-select: none;
        }
        .quote-text {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(1.5rem, 3.5vw, 2.4rem);
            color: var(--green-dark); max-width: 720px;
            margin: 0 auto 16px; line-height: 1.3;
            position: relative;
        }
        .quote-attr {
            font-size: 0.85rem; color: rgba(26,46,16,0.5);
            font-weight: 600; letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* cta */
        .cta-final {
            background: linear-gradient(140deg, var(--green-dark) 0%, #2a4a18 100%);
            padding: 110px 24px;
            text-align: center; position: relative; overflow: hidden;
        }

        .cta-final::before {
            content: '🌿';
            position: absolute; font-size: 22rem;
            opacity: 0.04; top: 50%; right: -60px;
            transform: translateY(-50%);
            pointer-events: none;
        }
        .cta-final h2 {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            color: #fff; margin-bottom: 16px;
            position: relative;
        }
        .cta-final h2 em { color: var(--green-light); font-style: italic; }
        .cta-final p {
            font-size: 1.05rem; color: rgba(255,255,255,0.6);
            max-width: 460px; margin: 0 auto 40px;
            line-height: 1.75; position: relative;
        }
        .cta-btns { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; position: relative; }

        /* scroll reveal */
        .reveal {
            opacity: 0; transform: translateY(36px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }
        .reveal-delay-5 { transition-delay: 0.5s; }

        /* animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; } to { opacity: 1; }
        }

        .cta-ghost { color: #fff; border-color: rgba(255,255,255,0.35); }

        /* responsive */
        @media (max-width: 900px) {
            .bento-grid { grid-template-columns: 1fr 1fr; }
            .bento-card.wide { grid-column: span 2; }
            .steps-grid { grid-template-columns: 1fr; gap: 0; }
            .steps-grid::before { display: none; }
        }
        @media (max-width: 600px) {
            .bento-grid { grid-template-columns: 1fr; }
            .bento-card.wide { grid-column: span 1; }
            .hero-content { padding: 60px 20px; }
            .hero-stats { gap: 10px; }
            .hero-stat-pill { padding: 10px 14px; }
        }
    </style>

    <main>

        <!-- hero -->
        <section class="hero" id="heroSection">
            <div class="hero-content">
                <div class="hero-eyebrow">🌿 Your nutrition, simplified</div>

                <h1 class="hero-title">
                    Eat <span>Smarter,</span><br>Live Better.
                </h1>

                <p class="hero-desc">
                    Plan nutritious meals, discover recipes, and track your nutrition. All in one beautifully simple place.
                </p>

                <div class="hero-actions">
                    <a href="about.php" class="btn-ghost">Learn More</a>
                </div>


            </div>

            <div class="scroll-cue" onclick="document.querySelector('.sp').scrollIntoView({behavior:'smooth'})">
                <div class="scroll-chevron"></div>
            </div>
        </section>

        <!-- features bento -->
        <section class="sp">
            <div class="sp-inner">
                <div class="sp-label reveal">Everything you need</div>
                <h2 class="sp-heading reveal reveal-delay-1">Four tools.<br>One smart platform.</h2>
                <p class="sp-sub reveal reveal-delay-2">
                    From AI-powered chat to recipe discovery, SmartPlate has everything
                    you need to take control of what you eat.
                </p>

                <div class="bento-grid">


                    <div class="bento-card platebot wide reveal reveal-delay-1">
                        <span class="bento-card-tag">AI-Powered</span>
                        <span class="bento-icon">🤖</span>
                        <div class="bento-title">PlateBot — your nutrition AI</div>
                        <div class="bento-desc">Ask anything about food, calories, or your goals. PlateBot answers instantly.</div>
                        <div class="platebot-chat">
                            <div class="pb-bubble user">"What should I eat before a workout?"</div>
                            <div class="pb-bubble">"Try a banana with almond butter about 30–45 min before. Easy carbs + healthy fat for sustained energy 💪"</div>
                        </div>
                    </div>

                    <div class="bento-card reveal reveal-delay-2">
                        <span class="bento-card-tag">Discover</span>
                        <span class="bento-icon">📖</span>
                        <div class="bento-title">Recipe Generator</div>
                        <div class="bento-desc">Tell us your ingredients or dietary goals and we'll find the perfect recipe.</div>
                    </div>


                    <div class="bento-card reveal reveal-delay-1">
                        <span class="bento-card-tag">Track</span>
                        <span class="bento-icon">📊</span>
                        <div class="bento-title">Nutrition Tracker</div>
                        <div class="bento-desc">Log meals in seconds. See your calories, carbs, protein and fat — all in real time.</div>
                    </div>

                    <div class="bento-card reveal reveal-delay-2">
                        <span class="bento-card-tag">Ready to Eat</span>
                        <span class="bento-icon">🍱</span>
                        <div class="bento-title">Smart Meals</div>
                        <div class="bento-desc">Browse curated ready meals — nutritionally balanced and logged with one tap.</div>
                    </div>

                    <div class="bento-card reveal reveal-delay-3">
                        <span class="bento-card-tag">Plan Ahead</span>
                        <span class="bento-icon">🛒</span>
                        <div class="bento-title">Shopping List</div>
                        <div class="bento-desc">Auto-generate a grocery list from your meal plan. Never forget an ingredient again.</div>
                    </div>

                </div>
            </div>
        </section>

        <!-- how it works -->
        <section class="sp how-section">
            <div class="sp-inner">
                <div class="sp-label reveal">Simple by design</div>
                <h2 class="sp-heading reveal reveal-delay-1">Up and running<br>in three steps.</h2>
                <p class="sp-sub reveal reveal-delay-2">
                    No tutorials needed. SmartPlate gets you tracking and eating well in minutes.
                </p>

                <div class="steps-grid">
                    <div class="step-item reveal reveal-delay-1">
                        <div class="step-num-badge">1</div>
                        <span class="step-emoji">👤</span>
                        <div class="step-title">Create an Account</div>
                        <p class="step-desc">Sign up for free and set up your personal profile in minutes.</p>
                    </div>
                    <div class="step-item reveal reveal-delay-2">
                        <div class="step-num-badge">2</div>
                        <span class="step-emoji">✅</span>
                        <div class="step-title">Set Your Preferences</div>
                        <p class="step-desc">Tell us your dietary needs, goals, and how many meals you want per day.</p>
                    </div>
                    <div class="step-item reveal reveal-delay-3">
                        <div class="step-num-badge">3</div>
                        <span class="step-emoji">🍽️</span>
                        <div class="step-title">Get Your Meal Plan</div>
                        <p class="step-desc">Receive personalized meal plans and recipes tailored just for you.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- quote band -->
        <div class="quote-band">
            <p class="quote-text reveal">
                "Healthy eating doesn't have to be complicated. It just needs the right tools."
            </p>
        </div>

        <!-- cta -->
        <section class="cta-final">
            <h2 class="reveal">Ready to eat <em>smarter?</em></h2>
            <p class="reveal reveal-delay-1">
                Join SmartPlate today and take control of your nutrition.
                It's free, it's simple, and it actually works.
            </p>
            <div class="cta-btns reveal reveal-delay-2">
                <a href="signup.php" class="btn-primary">Get Started — It's Free</a>
                <a href="login.php" class="btn-ghost cta-ghost">Sign In</a>
            </div>
        </section>

    </main>

    <script>
        // hero parallax
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const hero = document.getElementById('heroSection');
            if (hero) hero.style.backgroundPositionY = `calc(50% + ${scrolled * 0.3}px)`;
        });

        // scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

<?php include('../includes/footer.php'); ?>