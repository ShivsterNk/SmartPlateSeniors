<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include('../config/db.php');
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) { echo json_encode(['success' => false]); exit; }

$meal_id = $_POST['meal_id'] ?? '';
if (!$meal_id) { echo json_encode(['success' => false]); exit; }

try {
    $pdo = getPDO();
    $pdo->prepare("DELETE FROM shopping_list_recipes WHERE user_id = ? AND meal_id = ?")->execute([$user_id, $meal_id]);
    $pdo->prepare("UPDATE shopping_lists SET list_json = NULL WHERE user_id = ?")->execute([$user_id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}