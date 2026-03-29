<?php
// ══════════════════════════════════════════
//  SmartPlate — favorites.php
// ══════════════════════════════════════════

session_start();
require_once __DIR__ . '/../config/db.php';

// ── Redirect to login if not authenticated ──
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo    = getPDO();
$userId = (int) $_SESSION['user_id'];

// ── Fetch user name ──────────────────────────
$stmtUser = $pdo->prepare("SELECT name FROM users WHERE user_id = ?");
$stmtUser->execute([$userId]);
$userData = $stmtUser->fetch();
$name     = htmlspecialchars($userData['name'] ?? '');
$initial  = strtoupper(mb_substr($name, 0, 1));

// ── Handle remove favorite ───────────────────
// Called when user clicks Remove button (POST with meal_id)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_meal_id'])) {
    $removeMealId = trim($_POST['remove_meal_id']);
    $stmtRemove = $pdo->prepare("
        DELETE FROM favorites WHERE user_id = ? AND meal_id = ?
    ");
    $stmtRemove->execute([$userId, $removeMealId]);
    // Redirect to avoid resubmit on refresh
    header('Location: favorites.php');
    exit;
}

// ── Fetch all favorites ──────────────────────
$stmtFavs = $pdo->prepare("
    SELECT meal_id, meal_name, meal_thumb, meal_category, meal_area, saved_at
    FROM favorites
    WHERE user_id = ?
    ORDER BY saved_at DESC
");
$stmtFavs->execute([$userId]);
$favorites = $stmtFavs->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Favorites | SmartPlate</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #FEFAE0;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        /* ── NAVBAR ── */
        .navbar-custom { background-color: #283618; }
        .logo {
            color: white; font-size: 1.8rem;
            font-weight: 700; letter-spacing: 0.5px;
        }
        .nav-links { display: flex; gap: 40px; }
        .nav-links a {
            color: white; font-weight: 600;
            text-decoration: none; font-size: 1rem;
        }
        .nav-links a:hover, .nav-links a.active { text-decoration: underline; }

        /* ── PAGE HEADER ── */
        .page-header {
            background-color: #283618;
            color: white;
            padding: 40px 0 30px;
            margin-bottom: 40px;
        }
        .page-header h1 { font-size: 2rem; font-weight: 700; }
        .page-header p { opacity: 0.75; margin: 0; }

        /* ── RECIPE CARDS ── */
        .recipe-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            background: #fff;
            height: 100%;
        }
        .recipe-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.14);
        }
        .recipe-card img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
        }
        .recipe-card .card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
        }
        .recipe-card .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #283618;
            margin-bottom: 4px;
        }
        .recipe-card .card-meta {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 14px;
        }
        .recipe-card .card-meta span {
            background: #edf3eb;
            color: #2d4a2d;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
            margin-right: 4px;
        }
        .btn-view {
            background-color: #283618;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            transition: background 0.2s;
            text-align: center;
        }
        .btn-view:hover { background-color: #1f2a12; color: white; }
        .btn-remove {
            background: none;
            border: 1px solid #e63946;
            color: #e63946;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            margin-top: 8px;
        }
        .btn-remove:hover {
            background-color: #e63946;
            color: white;
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }
        .empty-state .empty-icon { font-size: 4rem; margin-bottom: 16px; }
        .empty-state h4 { color: #283618; font-weight: 700; margin-bottom: 8px; }
        .empty-state p { color: #888; margin-bottom: 24px; }
        .btn-explore {
            background-color: #283618;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-explore:hover { background-color: #1f2a12; color: white; }

        /* ── SAVED DATE ── */
        .saved-date {
            font-size: 0.75rem;
            color: #aaa;
            margin-top: auto;
            padding-top: 8px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<header class="navbar-custom">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <div class="logo">Smart Plate</div>
        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="explore.php">Explore</a>
            <a href="favorites.php" class="active">Favorites</a>
            <a href="logout.php">Sign Out</a>
        </nav>
    </div>
</header>

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="container">
        <h1>&#9829; My Favorites</h1>
        <p>
            <?= $name ?>'s saved recipes —
            <?= count($favorites) ?> recipe<?= count($favorites) !== 1 ? 's' : '' ?> saved
        </p>
    </div>
</div>

<!-- CONTENT -->
<div class="container pb-5">

    <?php if (!empty($favorites)): ?>

        <div class="row g-4">
            <?php foreach ($favorites as $fav): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="recipe-card card">

                        <img src="<?= htmlspecialchars($fav['meal_thumb']) ?>"
                             alt="<?= htmlspecialchars($fav['meal_name']) ?>">

                        <div class="card-body">
                            <div class="card-title"><?= htmlspecialchars($fav['meal_name']) ?></div>

                            <div class="card-meta">
                                <?php if ($fav['meal_category']): ?>
                                    <span><?= htmlspecialchars($fav['meal_category']) ?></span>
                                <?php endif; ?>
                                <?php if ($fav['meal_area']): ?>
                                    <span><?= htmlspecialchars($fav['meal_area']) ?></span>
                                <?php endif; ?>
                            </div>

                            <a href="recipe_detail.php?id=<?= htmlspecialchars($fav['meal_id']) ?>"
                               class="btn-view">
                                View Recipe
                            </a>

                            <!-- Remove from favorites -->
                            <form method="POST" action="favorites.php"
                                  onsubmit="return confirm('Remove <?= htmlspecialchars(addslashes($fav['meal_name'])) ?> from favorites?')">
                                <input type="hidden" name="remove_meal_id"
                                       value="<?= htmlspecialchars($fav['meal_id']) ?>">
                                <button type="submit" class="btn-remove">
                                    &#9825; Remove
                                </button>
                            </form>

                            <div class="saved-date">
                                Saved <?= date('M j, Y', strtotime($fav['saved_at'])) ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <!-- Empty state -->
        <div class="empty-state">
            <div class="empty-icon">&#9825;</div>
            <h4>No favorites yet</h4>
            <p>Search for recipes and click the &#9829; button to save them here.</p>
            <a href="recipe_generator.php" class="btn-explore">Find Recipes</a>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
