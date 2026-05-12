<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include('../config/db.php');

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$meal_id   = $_POST['meal_id'] ?? '';
$meal_name = $_POST['meal_name'] ?? '';

if (!$meal_id || !$meal_name) {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
    exit;
}

try {
    $stmt = getPDO()->prepare("
        INSERT IGNORE INTO shopping_list_recipes (user_id, meal_id, meal_name)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$user_id, $meal_id, $meal_name]);

    // Clear cached list so it regenerates with new recipe
    $clear = getPDO()->prepare("
        UPDATE shopping_lists SET list_json = NULL WHERE user_id = ?
    ");
    $clear->execute([$user_id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}