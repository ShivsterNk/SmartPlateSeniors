<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/api-keys.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo    = getPDO();
$userId = (int) $_SESSION['user_id'];
$name   = htmlspecialchars($_SESSION['user_name'] ?? '');
$today  = date('Y-m-d');

// Fetch today's logged meals
$stmtLogs = $pdo->prepare("
    SELECT id, meal_type, food_name, calories, carbs_g, protein_g, fat_g, source, logged_at
    FROM nutrition_logs
    WHERE user_id = ? AND log_date = ?
    ORDER BY logged_at ASC
");
$stmtLogs->execute([$userId, $today]);
$logs = $stmtLogs->fetchAll();

// Calculate today's totals
$totalCalories = array_sum(array_column($logs, 'calories'));
$totalCarbs    = array_sum(array_column($logs, 'carbs_g'));
$totalProtein  = array_sum(array_column($logs, 'protein_g'));
$totalFat      = array_sum(array_column($logs, 'fat_g'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Tracker | SmartPlate</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/SmartPlateSeniors/assets/spstyle.css">
    <style>
        body { background: #f5f2ec; margin: 0; font-family: 'DM Sans', sans-serif; }

        .page-banner {
            background: linear-gradient(135deg, #1a2e10, #3a5220);
            color: white;
            padding: 14px 0;
            margin-top: 70px;
            margin-bottom: 0;
            border-bottom: 3px solid #a7c957;
            text-align: center;
        }
        .banner-icon { font-size: 2rem; margin-bottom: 8px; }
        .page-banner h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem; font-weight: 400;
            margin: 0 0 6px;
        }
        .page-banner p { color: rgba(255,255,255,0.7); font-size: 0.9rem; margin: 0; }

        .tracker-wrap { max-width: 900px; margin: 0 auto; padding: 32px 16px 60px; }

        /* summary cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }
        .summary-card {
            background: white;
            border-radius: 14px;
            padding: 20px 16px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            border-top: 4px solid #a7c957;
        }
        .summary-card.calories { border-top-color: #283618; }
        .summary-card.carbs    { border-top-color: #4a7c4a; }
        .summary-card.protein  { border-top-color: #a8c5a0; }
        .summary-card.fat      { border-top-color: #d8e8d4; }
        .summary-value {
            font-size: 2rem; font-weight: 700;
            color: #283618; line-height: 1;
            margin-bottom: 4px;
        }
        .summary-label { font-size: 0.78rem; color: #7a8a7a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }

        /* search */
        .section-card {
            background: white;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            margin-bottom: 24px;
        }
        .section-title {
            font-size: 1rem; font-weight: 700;
            color: #283618; margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }

        /* log items */
        .log-item {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border: 1px solid #edf3eb;
            border-radius: 10px; margin-bottom: 8px;
            background: white;
        }
        .log-item-left { display: flex; align-items: center; gap: 12px; }
        .log-meal-type {
            background: #edf3eb; color: #283618;
            font-size: 0.72rem; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
            text-transform: uppercase; letter-spacing: 0.04em;
            white-space: nowrap;
        }
        .log-food-name { font-weight: 600; font-size: 0.9rem; color: #283618; }
        .log-food-macros { font-size: 0.78rem; color: #7a8a7a; margin-top: 2px; }
        .log-item-right { display: flex; align-items: center; gap: 12px; }
        .log-calories { font-weight: 700; font-size: 0.95rem; color: #283618; }
        .btn-delete {
            background: none; border: 1px solid #fca5a5;
            color: #e63946; border-radius: 8px;
            padding: 4px 10px; font-size: 0.78rem;
            font-weight: 600; cursor: pointer;
            transition: all 0.15s;
        }
        .btn-delete:hover { background: #e63946; color: white; }

        .empty-log {
            text-align: center; padding: 32px;
            color: #7a8a7a; font-size: 0.9rem;
        }
        .empty-log .empty-icon { font-size: 2.5rem; margin-bottom: 10px; }

        /* loading */
        .search-loading {
            text-align: center; padding: 20px;
            color: #7a8a7a; font-size: 0.9rem;
            display: none;
        }

        @media (max-width: 768px) {
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
            .search-row { flex-direction: column; }
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="page-banner">
    <div class="container text-center">
        <div class="banner-icon">📊</div>
        <h1>Nutrition Tracker</h1>
        <p>Track your daily nutrition and log your meals</p>
    </div>
</div>

<div class="tracker-wrap">

    <!-- summary cards -->
    <div class="summary-grid">
        <div class="summary-card calories">
            <div class="summary-value" id="totalCalories"><?= round($totalCalories) ?></div>
            <div class="summary-label">Calories</div>
        </div>
        <div class="summary-card carbs">
            <div class="summary-value" id="totalCarbs"><?= round($totalCarbs, 1) ?></div>
            <div class="summary-label">Carbs (g)</div>
        </div>
        <div class="summary-card protein">
            <div class="summary-value" id="totalProtein"><?= round($totalProtein, 1) ?></div>
            <div class="summary-label">Protein (g)</div>
        </div>
        <div class="summary-card fat">
            <div class="summary-value" id="totalFat"><?= round($totalFat, 1) ?></div>
            <div class="summary-label">Fat (g)</div>
        </div>
    </div>

    <!-- today's log -->
    <div class="section-card">
        <div class="section-title">📋 Today's Log — <?= date('F j, Y') ?></div>
        <div id="todayLog">
            <?php if (!empty($logs)): ?>
                <?php foreach ($logs as $log): ?>
                    <div class="log-item" id="log-<?= $log['id'] ?>">
                        <div class="log-item-left">
                            <span class="log-meal-type"><?= htmlspecialchars($log['meal_type']) ?></span>
                            <div>
                                <div class="log-food-name"><?= htmlspecialchars($log['food_name']) ?></div>
                                <div class="log-food-macros">
                                    Carbs: <?= round($log['carbs_g'], 1) ?>g &nbsp;·&nbsp;
                                    Protein: <?= round($log['protein_g'], 1) ?>g &nbsp;·&nbsp;
                                    Fat: <?= round($log['fat_g'], 1) ?>g
                                </div>
                            </div>
                        </div>
                        <div class="log-item-right">
                            <div class="log-calories"><?= round($log['calories']) ?> kcal</div>
                            <button class="btn-delete" onclick="deleteLog(<?= $log['id'] ?>)">Remove</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-log" id="emptyLog">
                    <div class="empty-icon">🥗</div>
                    <div>No meals logged yet today.</div>
                    <div style="margin-top:8px; font-size:0.85rem;">
                        Go to your <a href="dashboard.php" style="color:#283618; font-weight:700;">Dashboard</a>
                        and click a meal card to log it.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- weekly history -->
    <div class="section-card">
        <div class="section-title">📅 This Week</div>
        <div id="weeklyHistory">
            <?php
            $stmtWeek = $pdo->prepare("
                SELECT log_date, 
                       COUNT(*) as meals_count,
                       COALESCE(SUM(calories), 0) as total_cals
                FROM nutrition_logs
                WHERE user_id = ? 
                AND log_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                GROUP BY log_date
                ORDER BY log_date DESC
            ");
            $stmtWeek->execute([$userId]);
            $weekLogs = $stmtWeek->fetchAll();
            ?>
            <?php if (!empty($weekLogs)): ?>
                <?php foreach ($weekLogs as $day): ?>
                    <div class="log-item">
                        <div class="log-item-left">
                            <span class="log-meal-type">
                                <?= date('D', strtotime($day['log_date'])) ?>
                            </span>
                            <div>
                                <div class="log-food-name">
                                    <?= date('F j', strtotime($day['log_date'])) ?>
                                </div>
                                <div class="log-food-macros">
                                    <?= $day['meals_count'] ?> meal<?= $day['meals_count'] > 1 ? 's' : '' ?> logged
                                </div>
                            </div>
                        </div>
                        <div class="log-item-right">
                            <div class="log-calories"><?= round($day['total_cals']) ?> kcal</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-log">
                    <div class="empty-icon">📅</div>
                    <div>No meals logged this week yet.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
<script>
    async function deleteLog(id) {
        try {
            const res  = await fetch('../PHP/delete_log.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const data = await res.json();
            if (data.status === 'success') {
                document.getElementById('log-' + id)?.remove();
                location.reload();
            }
        } catch (err) {
            alert('Could not remove. Try again.');
        }
    }
</script>


</body>
</html>