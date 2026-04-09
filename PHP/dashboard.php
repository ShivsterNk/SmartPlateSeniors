<?php
// ══════════════════════════════════════════
//  SmartPlate — dashboard.php
// ══════════════════════════════════════════

session_start();
require_once __DIR__ . '/../config/db.php';

// ── Redirect to login if not authenticated ──
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// ── DB connection ────────────────────────────
$pdo    = getPDO();
$userId = (int) $_SESSION['user_id'];

// ── Fetch user profile ───────────────────────
$stmtUser = $pdo->prepare("SELECT user_id, name, email FROM users WHERE user_id = ?");
$stmtUser->execute([$userId]);
$userData = $stmtUser->fetch();

if (!$userData) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

$name    = htmlspecialchars($userData['name']);
$email   = htmlspecialchars($userData['email']);
$initial = strtoupper(mb_substr($userData['name'], 0, 1));

// ── Fetch dietary preferences from survey table ──
$dietaryPrefs = [];
try {
    $stmtSurvey = $pdo->prepare("
        SELECT preference_name
        FROM survey
        WHERE user_id = ?
        ORDER BY preference_name ASC
    ");
    $stmtSurvey->execute([$userId]);
    $dietaryPrefs = $stmtSurvey->fetchAll();
} catch (PDOException $e) {
    $dietaryPrefs = [];
}

// ── Fetch favorites ──────────────────────────
$favorites = [];
try {
    $stmtFavs = $pdo->prepare("
        SELECT meal_id, meal_name, meal_thumb, meal_category, meal_area
        FROM favorites
        WHERE user_id = ?
        ORDER BY saved_at DESC
        LIMIT 5
    ");
    $stmtFavs->execute([$userId]);
    $favorites = $stmtFavs->fetchAll();
} catch (PDOException $e) {
    $favorites = [];
}

// ── Fetch recent foods ───────────────────────
$recentFoods = [];
try {
    $stmtRecent = $pdo->prepare("
        SELECT food_name, calories, viewed_at
        FROM recent_foods
        WHERE user_id = ?
        ORDER BY viewed_at DESC
        LIMIT 5
    ");
    $stmtRecent->execute([$userId]);
    $recentFoods = $stmtRecent->fetchAll();
} catch (PDOException $e) {
    $recentFoods = [];
}

// ── Fix: define display dates here ──────────
$displayDate  = date('F j, Y');
$displayMonth = date('F Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SmartPlate — Dashboard</title>
    <link rel="stylesheet" href="../js/dashboard.css" />
</head>
<body>

<nav>
    <a class="nav-logo" id="navLogo" href="#" title="Toggle sidebar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9.5L12 3l9 6.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/>
            <path d="M9 21V12h6v9"/>
        </svg>
        SmartPlate
    </a>
    <ul class="nav-links">
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="../Nutrition Explore Page/nutrition-explorer.php">Explore</a></li>
        <li><a href="favorites.php">Favorites</a></li>
    </ul>
    <div class="nav-right">
        <button class="nav-profile-btn" id="navProfileBtn" aria-label="Open profile menu">
            <div class="avatar"><?= $initial ?></div>
            <span class="nav-username"><?= $name ?></span>
            <span class="nav-chevron">▾</span>
        </button>
    </div>
</nav>

<div class="profile-dropdown" id="profileDropdown" role="menu">
    <div class="pd-header">
        <div class="pd-avatar"><?= $initial ?></div>
        <div>
            <div class="pd-name"><?= $name ?></div>
            <div class="pd-email"><?= $email ?></div>
            <?php if (!empty($dietaryPrefs)): ?>
                <div style="display:flex; flex-wrap:wrap; gap:4px; margin-top:8px;">
                    <?php foreach ($dietaryPrefs as $pref): ?>
                        <span style="background:var(--green-mid); color:#fff;
                         font-size:0.68rem; font-weight:600;
                         padding:2px 8px; border-radius:20px;">
                            <?= htmlspecialchars($pref['preference_name']) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="pd-body">
        <a class="pd-item" href="profile.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            Edit Profile
        </a>
        <a class="pd-item" href="survey.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
            </svg>
            Dietary Preferences
        </a>
        <div class="pd-divider"></div>
        <a class="pd-item danger" href="logout.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18.36 6.64a9 9 0 11-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/>
            </svg>
            Log Out
        </a>
    </div>
</div>

<div class="overlay" id="overlay"></div>

<div class="app-layout">

    <aside id="sidebar">
        <a class="sidebar-item active"  href="/SmartPlateSeniors/Pages/platebot.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            PlateBot
        </>
        <a class="sidebar-item" href="nutrition-tracker.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Nutrition Tracker
        </a>
        <a class="sidebar-item" href="shopping-list.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            Shopping List Generator
        </a>
        <div class="sidebar-spacer"></div>
        <a class="logout-row" href="logout.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18.36 6.64a9 9 0 11-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/>
            </svg>
            Log Out
        </a>
    </aside>

    <main id="mainContent">
        <div class="main-col">

            <div class="page-header">
                <h1>Welcome Back, <?= $name ?>!</h1>
                <p>See a quick overview of your SmartPlate dashboard.</p>
            </div>

            <!-- Nutrition card -->
            <div class="card nutrition-card">
                <div class="nutrition-header">
                    <span class="card-title" style="margin:0">Today's Nutrition</span>
                    <div class="nutrition-date">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <?= $displayDate ?> ›
                    </div>
                </div>

                <div class="nutrition-body" id="nutritionBody">
                    <div class="donut-wrap">
                        <svg viewBox="0 0 140 140">
                            <circle cx="70" cy="70" r="55" fill="none" stroke="#edf3eb" stroke-width="16"/>
                            <circle cx="70" cy="70" r="55" fill="none" stroke="#4a7c4a" stroke-width="16"
                                    stroke-dasharray="138 207.4" stroke-dashoffset="86.35" stroke-linecap="round"/>
                            <circle cx="70" cy="70" r="55" fill="none" stroke="#a8c5a0" stroke-width="16"
                                    stroke-dasharray="86 259.4" stroke-dashoffset="-51.65" stroke-linecap="round"/>
                            <circle cx="70" cy="70" r="55" fill="none" stroke="#d8e8d4" stroke-width="16"
                                    stroke-dasharray="42 303.4" stroke-dashoffset="-137.65" stroke-linecap="round"/>
                        </svg>
                        <div class="donut-center">
                            <div class="donut-kcal">—</div>
                            <div class="donut-unit">kcal</div>
                        </div>
                    </div>

                    <div class="macros">
                        <div class="macro-row">
                            <div class="macro-label"><span>Carbs</span><span>— g</span></div>
                            <div class="bar-track"><div class="bar-fill" style="width:0%;background:#4a7c4a"></div></div>
                        </div>
                        <div class="macro-row">
                            <div class="macro-label"><span>Protein</span><span>— g</span></div>
                            <div class="bar-track"><div class="bar-fill" style="width:0%;background:#a8c5a0"></div></div>
                        </div>
                        <div class="macro-row">
                            <div class="macro-label"><span>Fat</span><span>— g</span></div>
                            <div class="bar-track"><div class="bar-fill" style="width:0%;background:#d8e8d4"></div></div>
                        </div>
                        <div class="daily-target">Log a meal to start tracking</div>
                    </div>

                    <div style="position:relative; padding-left:28px;">
                        <div style="position:absolute;left:0;top:0;display:flex;flex-direction:column;
                        justify-content:space-between;height:108px;font-size:0.65rem;
                        color:var(--text-light);text-align:right;width:24px;">
                            <span>2.4k</span><span>2.0k</span><span>1.8k</span><span>1.5k</span><span>1.2k</span>
                        </div>
                        <div class="bar-chart">
                            <div class="bc-col"><div class="bc-bar" style="height:0%"></div><div class="bc-label">Mon</div></div>
                            <div class="bc-col"><div class="bc-bar" style="height:0%"></div><div class="bc-label">Tue</div></div>
                            <div class="bc-col"><div class="bc-bar" style="height:0%"></div><div class="bc-label">Wed</div></div>
                            <div class="bc-col"><div class="bc-bar today" style="height:0%"></div><div class="bc-label">Thu</div></div>
                            <div class="bc-col"><div class="bc-bar" style="height:0%"></div><div class="bc-label">Fri</div></div>
                            <div class="bc-col"><div class="bc-bar" style="height:0%"></div><div class="bc-label">Sat</div></div>
                        </div>
                    </div>
                </div>

                <div class="nutrition-expand" id="nutritionExpand">
                    <button class="expand-btn" id="expandBtn" aria-label="Toggle nutrition details">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Today's Meals -->
            <div class="card">
                <div class="meals-header">
                    <div class="meals-title-group">
                        <span class="card-title" style="margin:0">Today's Meals</span>
                    </div>
                    <div class="calendar-nav">
                        <button class="cal-btn">‹</button>
                        <div class="cal-month"><?= $displayMonth ?></div>
                        <div class="cal-days">
                            <span class="cal-day">Mon</span>
                            <span class="cal-day">Tue</span>
                            <span class="cal-day">Wed</span>
                            <span class="cal-day">Thu</span>
                            <span class="cal-day">Fri</span>
                        </div>
                        <button class="cal-btn">›</button>
                    </div>
                </div>
                <div class="meal-cards-scroll">
                    <div style="grid-column:1/-1; text-align:center; padding:32px 0;
                      color:var(--text-light); font-size:0.9rem;">
                        No meals logged yet — meals will appear here once your meal plan is set up.
                    </div>
                </div>
            </div>

        </div><!-- /.main-col -->

        <div class="right-col">

            <!-- Recent Foods -->
            <div class="card">
                <div class="card-title">Recent Foods</div>
                <?php if (!empty($recentFoods)): ?>
                    <div class="fav-list">
                        <?php foreach ($recentFoods as $food): ?>
                            <div class="fav-item">
                                <div class="fav-img">🍽️</div>
                                <div>
                                    <div class="fav-name"><?= htmlspecialchars($food['food_name']) ?></div>
                                    <div class="fav-kcal">
                                        <?= $food['calories'] ? htmlspecialchars($food['calories']) . ' kcal' : '' ?>
                                        &nbsp;·&nbsp;
                                        <?= date('M j', strtotime($food['viewed_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="../Nutrition Explore Page/nutrition-explorer.php" class="view-all-btn">Explore More ›</a>
                <?php else: ?>
                    <p style="font-size:0.85rem; color:var(--text-light); padding:4px 0 14px;">
                        No recently viewed foods yet.
                    </p>
                    <a href="../Nutrition Explore Page/nutrition-explorer.php" class="view-all-btn">Start Exploring ›</a>
                <?php endif; ?>
            </div>

            <!-- Favorites -->
            <div class="card favorites-card">
                <div class="card-title">Favorites</div>
                <?php if (!empty($favorites)): ?>
                    <div class="fav-list">
                        <?php foreach ($favorites as $fav): ?>
                            <div class="fav-item">
                                <div class="fav-img">
                                    <img src="<?= htmlspecialchars($fav['meal_thumb']) ?>"
                                         alt="<?= htmlspecialchars($fav['meal_name']) ?>"
                                         style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                                </div>
                                <div>
                                    <div class="fav-name">
                                        <a href="recipe_detail.php?id=<?= htmlspecialchars($fav['meal_id']) ?>"
                                           style="color:var(--text-dark);text-decoration:none;">
                                            <?= htmlspecialchars($fav['meal_name']) ?>
                                        </a>
                                    </div>
                                    <div class="fav-kcal">
                                        <?= htmlspecialchars($fav['meal_category']) ?>
                                        <?= $fav['meal_area'] ? '· ' . htmlspecialchars($fav['meal_area']) : '' ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="recipe_generator.php" class="view-all-btn">Save More Recipes ›</a>
                <?php else: ?>
                    <p style="font-size:0.85rem; color:var(--text-light); padding:4px 0 14px;">
                        No favorites saved yet.
                    </p>
                    <a href="recipe_generator.php" class="view-all-btn">Find Recipes ›</a>
                <?php endif; ?>
            </div>

        </div><!-- /.right-col -->
    </main>

</div><!-- /.app-layout -->

<script src="../js/dashboard.js"></script>
</body>
</html>