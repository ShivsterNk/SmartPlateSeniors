<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$pdo    = getPDO();
$userId = (int) $_SESSION['user_id'];

$input    = json_decode(file_get_contents('php://input'), true);
$mealType = trim($input['meal_type'] ?? '');
$mealName = trim($input['meal_name'] ?? '');
$logDate  = trim($input['log_date'] ?? date('Y-m-d'));

if (!$mealType || !$mealName) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing meal info']);
    exit;
}

// Check if already logged today
$stmtCheck = $pdo->prepare("
    SELECT id FROM nutrition_logs 
    WHERE user_id = ? AND log_date = ? AND meal_type = ?
");
$stmtCheck->execute([$userId, $logDate, $mealType]);

if ($stmtCheck->fetch()) {
    echo json_encode(['status' => 'already_logged', 'message' => 'Already logged!']);
    exit;
}

// Save to nutrition_logs
$stmtInsert = $pdo->prepare("
    INSERT INTO nutrition_logs (user_id, log_date, meal_type, food_name, source)
    VALUES (?, ?, ?, ?, 'meal_plan')
");
$stmtInsert->execute([$userId, $logDate, $mealType, $mealName]);

echo json_encode(['status' => 'success', 'message' => 'Meal logged!']);