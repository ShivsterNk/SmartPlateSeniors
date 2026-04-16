<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$pdo     = getPDO();
$userId  = (int) $_SESSION['user_id'];
$logDate = $_GET['date'] ?? date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT 
        COALESCE(SUM(calories), 0)  AS total_calories,
        COALESCE(SUM(carbs_g), 0)   AS total_carbs,
        COALESCE(SUM(protein_g), 0) AS total_protein,
        COALESCE(SUM(fat_g), 0)     AS total_fat,
        COUNT(*) AS meals_logged
    FROM nutrition_logs
    WHERE user_id = ? AND log_date = ?
");
$stmt->execute([$userId, $logDate]);
$totals = $stmt->fetch();

// Weekly data for bar chart
$stmtWeek = $pdo->prepare("
    SELECT log_date, COALESCE(SUM(calories), 0) AS daily_calories
    FROM nutrition_logs
    WHERE user_id = ? 
    AND log_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY log_date
    ORDER BY log_date ASC
");
$stmtWeek->execute([$userId]);
$weeklyData = $stmtWeek->fetchAll();

header('Content-Type: application/json');
echo json_encode([
    'calories' => round($totals['total_calories']),
    'carbs'    => round($totals['total_carbs'], 1),
    'protein'  => round($totals['total_protein'], 1),
    'fat'      => round($totals['total_fat'], 1),
    'meals_logged' => (int) $totals['meals_logged'],
    'weekly'   => $weeklyData
]);
