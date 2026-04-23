<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | SmartPlate</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/SmartPlateSeniors/assets/spstyle.css">
    <style>
        :root {
            --forest:   #1a2e10;
            --green:    #283618;
            --mid:      #4a7c4a;
            --lime:     #a7c957;
            --cream:    #f5f2ec;
            --white:    #ffffff;
            --muted:    #7a8a7a;
            --card-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--cream);
            font-family: 'DM Sans', sans-serif;
            color: var(--green);
            overflow-x: hidden;
        }

        /* ── HERO ── */
        .hero {
            min-height: 92vh;
            background: linear-gradient(160deg, var(--forest) 0%, #2d4a1a 55%, #3d6b2a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            margin-top: 70px;
            padding: 80px 24px;
        }

        /* floating food emojis */
        .hero-floats {
            position: absolute; inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .float-item {
            position: absolute;
            font-size: 2.4rem;
            opacity: 0.18;
            animation: floatUp linear infinite;
            user-select: none;
        }
        @keyframes floatUp {
            0%   { transform: translateY(110vh) rotate(0deg);   opacity: 0;    }
            10%  { opacity: 0.18; }
            90%  { opacity: 0.18; }
            100% { transform: translateY(-20vh) rotate(360deg); opacity: 0;    }
        }

        /* big decorative ring */
        .hero-ring {
            position: absolute;
            width: 700px; height: 700px;
            border-radius: 50%;
            border: 2px solid rgba(167,201,87,0.15);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: spin 40s linear infinite;
        }
        .hero-ring-2 {
            width: 500px; height: 500px;
            border-color: rgba(167,201,87,0.1);
            animation-duration: 28s;
            animation-direction: reverse;
        }
        @keyframes spin { to { transform: translate(-50%,-50%) rotate(360deg); } }

        .hero-content {
            position: relative; z-index: 2;
            text-align: center; max-width: 780px;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(167,201,87,0.18);
            border: 1px solid rgba(167,201,87,0.4);
            border-radius: 999px;
            padding: 6px 18px;
            font-size: 0.8rem; font-weight: 700;
            color: var(--lime);
            letter-spacing: 0.08em; text-transform: uppercase;
            margin-bottom: 28px;
            animation: fadeDown 0.6s ease both;
        }
        .hero-title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            color: var(--white);
            line-height: 1.05;
            margin-bottom: 24px;
            animation: fadeDown 0.6s 0.15s ease both;
        }
        .hero-title em {
            color: var(--lime);
            font-style: italic;
        }
        .hero-sub {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: rgba(255,255,255,0.72);
            line-height: 1.7;
            max-width: 560px;
            margin: 0 auto 40px;
            animation: fadeDown 0.6s 0.3s ease both;
        }
        .hero-cta {
            display: inline-flex; align-items: center; gap: 10px;
            background: var(--lime);
            color: var(--forest);
            font-weight: 700; font-size: 1rem;
            padding: 14px 32px;
            border-radius: 999px;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            animation: fadeDown 0.6s 0.45s ease both;
        }
        .hero-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(167,201,87,0.4);
            color: var(--forest);
        }

        /* scroll indicator */
        .scroll-hint {
            position: absolute; bottom: 32px; left: 50%;
            transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.4); font-size: 0.75rem; letter-spacing: 0.1em;
            animation: fadeDown 0.6s 0.7s ease both;
        }
        .scroll-arrow {
            width: 20px; height: 20px;
            border-right: 2px solid rgba(255,255,255,0.3);
            border-bottom: 2px solid rgba(255,255,255,0.3);
            transform: rotate(45deg);
            animation: bounce 1.5s ease infinite;
        }
        @keyframes bounce { 0%,100%{transform:rotate(45deg) translateY(0)} 50%{transform:rotate(45deg) translateY(6px)} }

        @keyframes fadeDown {
            from { opacity:0; transform: translateY(-20px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* ── SECTION SHARED ── */
        section { padding: 96px 24px; }
        .section-inner { max-width: 1100px; margin: 0 auto; }

        .section-label {
            font-size: 0.75rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--mid); margin-bottom: 12px;
        }
        .section-heading {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--forest); line-height: 1.15;
            margin-bottom: 16px;
        }
        .section-sub {
            font-size: 1.05rem; color: var(--muted);
            line-height: 1.7; max-width: 560px;
        }

        /* ── WHY WE BUILT IT ── */
        .why-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
            margin-top: 56px;
        }
        .why-visual {
            position: relative;
        }
        .why-big-number {
            font-family: 'DM Serif Display', serif;
            font-size: 11rem;
            color: var(--lime);
            opacity: 0.15;
            line-height: 1;
            position: absolute;
            top: -20px; left: -20px;
            pointer-events: none;
            user-select: none;
        }
        .why-stat-stack {
            display: flex; flex-direction: column; gap: 20px;
            position: relative; z-index: 1;
        }
        .why-stat {
            background: white;
            border-radius: 16px;
            padding: 24px 28px;
            box-shadow: var(--card-shadow);
            border-left: 5px solid var(--lime);
            transform: translateX(0);
            transition: transform 0.3s;
        }
        .why-stat:hover { transform: translateX(6px); }
        .why-stat-num {
            font-family: 'DM Serif Display', serif;
            font-size: 2.2rem; color: var(--forest);
            line-height: 1; margin-bottom: 4px;
        }
        .why-stat-label { font-size: 0.88rem; color: var(--muted); font-weight: 500; }

        .why-text p {
            font-size: 1.05rem; color: #4a5a4a;
            line-height: 1.8; margin-bottom: 20px;
        }
        .why-text p:last-child { margin-bottom: 0; }
        .why-text strong { color: var(--forest); }

        /* ── FEATURES TABS ── */
        .features-section { background: var(--forest); }
        .features-section .section-label { color: var(--lime); }
        .features-section .section-heading { color: white; }
        .features-section .section-sub { color: rgba(255,255,255,0.6); }

        .tabs-nav {
            display: flex; gap: 8px;
            margin: 40px 0 0;
            flex-wrap: wrap;
        }
        .tab-btn {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.65);
            border-radius: 999px;
            padding: 10px 22px;
            font-size: 0.88rem; font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.25s;
        }
        .tab-btn:hover { background: rgba(255,255,255,0.14); color: white; }
        .tab-btn.active {
            background: var(--lime);
            border-color: var(--lime);
            color: var(--forest);
        }

        .tabs-content { margin-top: 40px; }
        .tab-panel { display: none; }
        .tab-panel.active { display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: center; }

        .tab-panel-text h3 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem; color: white;
            margin-bottom: 16px; line-height: 1.2;
        }
        .tab-panel-text p {
            font-size: 1rem; color: rgba(255,255,255,0.65);
            line-height: 1.8; margin-bottom: 20px;
        }
        .tab-tag {
            display: inline-block;
            background: rgba(167,201,87,0.18);
            color: var(--lime);
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 0.78rem; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            margin-bottom: 16px;
        }

        .feature-visual {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 36px;
            display: flex; flex-direction: column; gap: 16px;
            min-height: 280px;
            justify-content: center;
        }
        .feature-visual-icon {
            font-size: 3.5rem; margin-bottom: 8px;
        }
        .feature-visual-line {
            height: 10px; border-radius: 999px;
            background: rgba(255,255,255,0.1);
            overflow: hidden;
        }
        .feature-visual-fill {
            height: 100%; border-radius: 999px;
            background: linear-gradient(90deg, var(--lime), var(--mid));
            animation: fillBar 2s ease infinite alternate;
        }
        @keyframes fillBar { from { width: 30%; } to { width: 90%; } }
        .feature-visual-fill.b { animation-delay: 0.4s; }
        .feature-visual-fill.c { animation-delay: 0.8s; }

        .chat-bubble {
            background: rgba(167,201,87,0.15);
            border: 1px solid rgba(167,201,87,0.3);
            border-radius: 16px 16px 16px 4px;
            padding: 14px 18px;
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem; line-height: 1.5;
        }
        .chat-bubble.right {
            background: rgba(255,255,255,0.08);
            border-radius: 16px 16px 4px 16px;
            align-self: flex-end;
        }
        .chat-wrap { display: flex; flex-direction: column; gap: 12px; }

        /* recipe card visual */
        .recipe-mini {
            background: rgba(255,255,255,0.06);
            border-radius: 12px; padding: 16px;
            display: flex; align-items: center; gap: 14px;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .recipe-mini-icon { font-size: 2rem; flex-shrink: 0; }
        .recipe-mini-name { font-weight: 600; color: white; font-size: 0.9rem; }
        .recipe-mini-sub { font-size: 0.78rem; color: rgba(255,255,255,0.5); margin-top: 2px; }
        .recipe-mini-cals {
            margin-left: auto; font-weight: 700;
            color: var(--lime); font-size: 0.88rem; white-space: nowrap;
        }

        /* ── MISSION STRIPE ── */
        .mission-stripe {
            background: var(--lime);
            padding: 64px 24px;
            text-align: center;
        }
        .mission-stripe h2 {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(1.8rem, 4vw, 3rem);
            color: var(--forest);
            max-width: 700px; margin: 0 auto 16px;
            line-height: 1.2;
        }
        .mission-stripe p {
            font-size: 1.05rem; color: rgba(26,46,16,0.75);
            max-width: 520px; margin: 0 auto;
            line-height: 1.7;
        }

        /* ── TEAM ── */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }
        .team-card {
            background: white;
            border-radius: 20px;
            padding: 32px 20px 24px;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: default;
        }
        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.12);
        }
        .team-avatar {
            width: 72px; height: 72px;
            border-radius: 50%;
            margin: 0 auto 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 700;
            color: white;
        }
        .team-name {
            font-weight: 700; font-size: 0.95rem;
            color: var(--forest); margin-bottom: 4px;
        }
        .team-role {
            font-size: 0.78rem; color: var(--muted);
            font-weight: 500;
        }

        /* ── CTA BOTTOM ── */
        .cta-section {
            background: linear-gradient(135deg, var(--forest), #2d4a1a);
            text-align: center;
            padding: 100px 24px;
            position: relative; overflow: hidden;
        }
        .cta-section::before {
            content: '🥗';
            position: absolute; font-size: 18rem;
            opacity: 0.04; top: 50%; left: 50%;
            transform: translate(-50%,-50%);
            pointer-events: none;
        }
        .cta-section h2 {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            color: white; margin-bottom: 16px;
            position: relative;
        }
        .cta-section p {
            color: rgba(255,255,255,0.65);
            font-size: 1.05rem; max-width: 480px;
            margin: 0 auto 36px; line-height: 1.7;
            position: relative;
        }
        .cta-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; }
        .btn-lime {
            background: var(--lime); color: var(--forest);
            font-weight: 700; padding: 14px 32px;
            border-radius: 999px; text-decoration: none;
            font-size: 0.95rem; transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-lime:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(167,201,87,0.4);
            color: var(--forest);
        }
        .btn-outline {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.3);
            color: white; font-weight: 600;
            padding: 14px 32px; border-radius: 999px;
            text-decoration: none; font-size: 0.95rem;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-outline:hover {
            border-color: white;
            background: rgba(255,255,255,0.08);
            color: white;
        }

        /* ── SCROLL REVEAL ── */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .why-grid, .tab-panel.active { grid-template-columns: 1fr; gap: 36px; }
            .why-big-number { font-size: 6rem; }
            section { padding: 64px 20px; }
            .tabs-nav { gap: 6px; }
            .tab-btn { padding: 8px 16px; font-size: 0.82rem; }
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<!-- ── HERO ── -->
<section class="hero">
    <div class="hero-ring hero-ring"></div>
    <div class="hero-ring hero-ring-2"></div>

    <div class="hero-floats" id="heroFloats"></div>

    <div class="hero-content">
        <div class="hero-badge">🌿 Our Story</div>
        <h1 class="hero-title">
            Food should be<br><em>simple, smart,</em><br>and enjoyable.
        </h1>
        <p class="hero-sub">
            We built SmartPlate because eating well shouldn't feel complicated.
            Whether you're tracking macros, discovering new recipes, or just
            trying to be a little healthier,we've got you.
        </p>
        <a href="#why" class="hero-cta">
            See our story
            <span>↓</span>
        </a>
    </div>

    <div class="scroll-hint">
        <div class="scroll-arrow"></div>
    </div>
</section>

<!-- ── WHY WE BUILT IT ── -->
<section id="why">
    <div class="section-inner">
        <div class="section-label reveal">The Problem We Saw</div>
        <h2 class="section-heading reveal reveal-delay-1">
            Healthy eating is hard<br>when you're going it alone.
        </h2>
        <p class="section-sub reveal reveal-delay-2">
            Calorie apps are overwhelming. Nutrition labels are confusing.
            And figuring out what to actually cook? Forget it.
            We wanted to change that.
        </p>

        <div class="why-grid">
            <div class="why-visual reveal reveal-delay-1">
                <div class="why-big-number">3</div>
                <div class="why-stat-stack">
                    <div class="why-stat">
                        <div class="why-stat-num">72%</div>
                        <div class="why-stat-label">of people don't track what they eat at all</div>
                    </div>
                    <div class="why-stat">
                        <div class="why-stat-num">1 in 3</div>
                        <div class="why-stat-label">adults struggle to meet daily nutritional goals</div>
                    </div>
                    <div class="why-stat">
                        <div class="why-stat-num">5 min</div>
                        <div class="why-stat-label">is all SmartPlate needs to log a full day's meals</div>
                    </div>
                </div>
            </div>

            <div class="why-text reveal reveal-delay-2">
                <p>
                    We're a team of students who felt this firsthand. Between classes, jobs,
                    and life in general, <strong>nobody has time to become a nutrition expert.</strong>
                    But everyone deserves to know what they're putting in their body.
                </p>
                <p>
                    SmartPlate was built as our senior capstone project, not just to fulfill
                    an assignment, but because we genuinely believed a better tool could exist.
                    One that <strong>meets you where you are</strong> and gives you everything
                    you need without the overwhelm.
                </p>
                <p>
                    From a quick meal log to a full recipe generator powered by AI,
                    every feature we built asks the same question:
                    <strong><em>"Does this actually make someone's life easier?"</em></strong>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── FEATURES TABS ── -->
<section class="features-section">
    <div class="section-inner">
        <div class="section-label reveal">What We Built</div>
        <h2 class="section-heading reveal reveal-delay-1">Everything you need.<br>Nothing you don't.</h2>
        <p class="section-sub reveal reveal-delay-2">
            Four powerful tools, all in one place. Click a feature to see what it does.
        </p>

        <div class="tabs-nav reveal reveal-delay-3">
            <button class="tab-btn active" data-tab="platebot">🤖 PlateBot AI</button>
            <button class="tab-btn" data-tab="recipes">📖 Recipe Generator</button>
            <button class="tab-btn" data-tab="tracker">📊 Nutrition Tracker</button>
            <button class="tab-btn" data-tab="meals">🍱 Smart Meals</button>
        </div>

        <div class="tabs-content reveal">

            <!-- PlateBot -->
            <div class="tab-panel active" id="tab-platebot">
                <div class="tab-panel-text">
                    <span class="tab-tag">AI-Powered</span>
                    <h3>Meet PlateBot, your personal nutrition AI.</h3>
                    <p>
                        Got a question about calories? Not sure if a meal fits your goals?
                        PlateBot is our built-in AI assistant (powered by Claude) that answers
                        your nutrition questions instantly, in plain English.
                    </p>
                    <p>
                        No googling, no guessing. Just ask.
                    </p>
                </div>
                <div class="feature-visual">
                    <div class="feature-visual-icon">🤖</div>
                    <div class="chat-wrap">
                        <div class="chat-bubble">"How much protein should I eat per day if I work out 3x a week?"</div>
                        <div class="chat-bubble right">"For someone exercising 3x weekly, aim for about 1.2–1.6g of protein per kg of body weight. For a 150lb person that's roughly 82–109g daily. Want me to suggest some high-protein meal ideas?"</div>
                    </div>
                </div>
            </div>

            <!-- Recipes -->
            <div class="tab-panel" id="tab-recipes">
                <div class="tab-panel-text">
                    <span class="tab-tag">Discover</span>
                    <h3>Generate recipes based on what you actually have.</h3>
                    <p>
                        Tell us your dietary preferences, the ingredients in your fridge,
                        or your calorie target, and we'll pull real recipes that match.
                    </p>
                    <p>
                        Powered by TheMealDB, so every recipe is tried, tested, and delicious.
                    </p>
                </div>
                <div class="feature-visual">
                    <div class="feature-visual-icon">📖</div>
                    <div class="recipe-mini">
                        <div class="recipe-mini-icon">🥗</div>
                        <div>
                            <div class="recipe-mini-name">Greek Chicken Bowl</div>
                            <div class="recipe-mini-sub">High protein · 25 min · Easy</div>
                        </div>
                        <div class="recipe-mini-cals">480 kcal</div>
                    </div>
                    <div class="recipe-mini">
                        <div class="recipe-mini-icon">🍝</div>
                        <div>
                            <div class="recipe-mini-name">Lemon Garlic Pasta</div>
                            <div class="recipe-mini-sub">Vegetarian · 20 min · Easy</div>
                        </div>
                        <div class="recipe-mini-cals">520 kcal</div>
                    </div>
                    <div class="recipe-mini">
                        <div class="recipe-mini-icon">🥘</div>
                        <div>
                            <div class="recipe-mini-name">Black Bean Tacos</div>
                            <div class="recipe-mini-sub">Vegan · 15 min · Easy</div>
                        </div>
                        <div class="recipe-mini-cals">390 kcal</div>
                    </div>
                </div>
            </div>

            <!-- Tracker -->
            <div class="tab-panel" id="tab-tracker">
                <div class="tab-panel-text">
                    <span class="tab-tag">Track</span>
                    <h3>Know exactly what you eat, every day.</h3>
                    <p>
                        Log meals in seconds and see your daily calories, carbs, protein,
                        and fat at a glance. No manual math. No spreadsheets.
                    </p>
                    <p>
                        Your weekly history is saved automatically so you can spot patterns
                        and make smarter choices over time.
                    </p>
                </div>
                <div class="feature-visual">
                    <div class="feature-visual-icon">📊</div>
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                                <span style="color:rgba(255,255,255,0.7);font-size:0.85rem;">Calories</span>
                                <span style="color:var(--lime);font-weight:700;font-size:0.85rem;">1,840 / 2,200</span>
                            </div>
                            <div class="feature-visual-line"><div class="feature-visual-fill" style="width:84%"></div></div>
                        </div>
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                                <span style="color:rgba(255,255,255,0.7);font-size:0.85rem;">Protein</span>
                                <span style="color:var(--lime);font-weight:700;font-size:0.85rem;">98g / 130g</span>
                            </div>
                            <div class="feature-visual-line"><div class="feature-visual-fill b" style="width:75%"></div></div>
                        </div>
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                                <span style="color:rgba(255,255,255,0.7);font-size:0.85rem;">Carbs</span>
                                <span style="color:var(--lime);font-weight:700;font-size:0.85rem;">210g / 275g</span>
                            </div>
                            <div class="feature-visual-line"><div class="feature-visual-fill c" style="width:76%"></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Smart Meals -->
            <div class="tab-panel" id="tab-meals">
                <div class="tab-panel-text">
                    <span class="tab-tag">Ready to Eat</span>
                    <h3>Pre-planned meals, ready when you are.</h3>
                    <p>
                        Browse our curated collection of ready meals — nutritionally balanced,
                        easy to prepare, and logged to your tracker in one tap.
                    </p>
                    <p>
                        Great for busy days when you just need a quick, healthy option
                        without any planning.
                    </p>
                </div>
                <div class="feature-visual">
                    <div class="feature-visual-icon">🍱</div>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <?php
                        $meals = [
                                ['🥙','Wrap','380 kcal'],
                                ['🍜','Ramen','450 kcal'],
                                ['🥑','Avo Toast','310 kcal'],
                                ['🍛','Curry','520 kcal'],
                        ];
                        foreach ($meals as $m): ?>
                            <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:14px 16px;text-align:center;flex:1;min-width:80px;">
                                <div style="font-size:1.8rem;margin-bottom:6px;"><?= $m[0] ?></div>
                                <div style="color:white;font-weight:600;font-size:0.82rem;"><?= $m[1] ?></div>
                                <div style="color:var(--lime);font-size:0.75rem;margin-top:2px;"><?= $m[2] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── MISSION STRIPE ── -->
<div class="mission-stripe">
    <h2 class="reveal">"Your health deserves a tool that actually works for you."</h2>
    <p class="reveal reveal-delay-1">That's the idea behind everything we built. Simple, accessible, and genuinely useful for everyone.</p>
</div>

<!-- ── TEAM ── -->
<section style="padding-bottom: 48px;">
    <div class="section-inner" style="text-align:center;">
        <div class="section-label reveal">The People Behind It</div>
        <h2 class="section-heading reveal reveal-delay-1" style="margin:0 auto 8px;">Built by students,<br>for everyone.</h2>
        <p class="section-sub reveal reveal-delay-2" style="margin:0 auto 0;">
            SmartPlate is a senior capstone project from Farmingdale State College.
            We're a team of five who turned a class assignment into something we're genuinely proud of.
        </p>

        <div class="team-grid">
            <?php
            $team = [
                    ['name' => 'Eunice',      'role' => 'Database, Backend & Frontend Design', 'color' => '#283618', 'initial' => 'E'],
                    ['name' => 'Derek',       'role' => 'AI Integration & Backend Development', 'color' => '#3a6a2a', 'initial' => 'D'],
                    ['name' => 'Marvin',      'role' => 'Frontend Development',                'color' => '#4a7c4a', 'initial' => 'M'],
                    ['name' => 'James',       'role' => 'Frontend Development',                'color' => '#6a9e3a', 'initial' => 'J'],
                    ['name' => 'Sivakumar',   'role' => 'Database & Testing',                  'color' => '#2a5a1a', 'initial' => 'S'],
            ];
            foreach ($team as $i => $member): ?>
                <div class="team-card reveal" style="transition-delay: <?= $i * 0.1 ?>s">
                    <div class="team-avatar" style="background: <?= $member['color'] ?>;">
                        <?= $member['initial'] ?>
                    </div>
                    <div class="team-name"><?= $member['name'] ?></div>
                    <div class="team-role"><?= $member['role'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>



<script>
    // ── Floating food emojis in hero ──
    const foods = ['🥗','🍎','🥦','🍋','🥑','🫐','🍇','🥕','🌽','🥝','🍊','🫑','🥜','🍓'];
    const container = document.getElementById('heroFloats');
    foods.forEach((emoji, i) => {
        const el = document.createElement('div');
        el.className = 'float-item';
        el.textContent = emoji;
        el.style.left = (5 + (i / foods.length) * 90) + '%';
        el.style.animationDuration = (12 + Math.random() * 14) + 's';
        el.style.animationDelay    = (Math.random() * 10) + 's';
        el.style.fontSize = (1.8 + Math.random() * 1.4) + 'rem';
        container.appendChild(el);
    });

    // ── Feature tabs ──
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });

    // ── Scroll reveal ──
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>

</body>
</html>