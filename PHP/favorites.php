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
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #283618;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 9999;
        }

        .toast.error {
            background: #e63946;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .heart-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            transition: 0.2s;
        }

        .heart-btn.active {
            color: red;
            transform: scale(1.2);
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
            background: linear-gradient(135deg, #1a2e10, #3a5220);
            color: white;
            padding: 20px 0;
            margin-top: 70px;
            margin-bottom: 0 !important;
            border-bottom: 3px solid #a7c957;
            text-align: center;
        }

        .page-header-icon {
            font-size: 1.8rem;
            margin-bottom: 6px;
        }

        .page-header h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.9rem;
            font-weight: 400;
            margin: 0 0 6px;
            letter-spacing: -0.01em;
        }

        .page-header p {
            margin: 0;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.65);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .recipe-count {
            background: #a7c957;
            color: #1a2e10;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        /* ── RECIPE CARDS ── */
        .recipe-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            background: #fff !important;
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
            background: #fff !important;
        }
        .recipe-card .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #283618 !important;
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

        .btn-shopping-list {
            background: none;
            border: 1px solid #606c38;
            color: #606c38;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            margin-top: 8px;
        }
        .btn-shopping-list:hover {
            background-color: #606c38;
            color: white;
        }

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
        .logo-img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<?php include('../includes/header.php'); ?>

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="container text-center">
        <div class="page-header-icon">❤️</div>
        <h1>My Favorites</h1>
        <p>
            <span class="recipe-count"><?= count($favorites) ?> recipe<?= count($favorites) !== 1 ? 's' : '' ?></span>
            saved by <?= $name ?>
        </p>
    </div>
</div>

<!-- CONTENT -->
<div class="container" style="padding-top: 16px; padding-bottom: 40px;">

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

                            <!-- Add to Shopping List -->
                            <button class="btn-shopping-list"
                                    onclick="addToShoppingList('<?= htmlspecialchars($fav['meal_id']) ?>', '<?= htmlspecialchars($fav['meal_name']) ?>')">
                                🛒 Add to Shopping List
                            </button>

                            <!-- Remove from favorites -->
                            <button class="btn-remove"
                                    onclick="removeFavorite('<?= htmlspecialchars($fav['meal_id']) ?>', this)">
                                &#9825; Remove
                            </button>

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

<script>
    function showToast(message, isError = false) {
        const toast = document.createElement("div");
        toast.className = "toast" + (isError ? " error" : "");
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add("show"), 100);

        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    function removeFavorite(mealId, btn) {
        fetch('favorites.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `remove_meal_id=${mealId}`
        })
            .then(() => {
                btn.closest('.col-md-4').remove();
                showToast("Removed from favorites ❌");
            })
            .catch(() => showToast("Could not remove", true));
    }

    function addToShoppingList(mealId, mealName) {
        const formData = new FormData();
        formData.append('meal_id', mealId);
        formData.append('meal_name', mealName);

        fetch('add_to_shopping_list.php', {
            method: 'POST',
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast(`Added "${mealName}" to shopping list! 🛒`);
                    setTimeout(() => {
                        window.location.href = 'shopping-list.php';
                    }, 1000); // short delay so user sees the toast first
                } else {
                    showToast('Could not add to shopping list', true);
                }
            })
            .catch(() => showToast('Error adding to shopping list', true));
    }
</script>

</body>
</html>