<?php
ob_start();

session_start();
require_once __DIR__ . '/../includes/db.php';

ob_clean();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$userId       = (int) $_SESSION['user_id'];
$mealId       = trim($_POST['meal_id']        ?? '');
$mealName     = trim($_POST['meal_name']      ?? '');
$mealThumb    = trim($_POST['meal_thumb']     ?? '');
$mealCategory = trim($_POST['meal_category']  ?? '');
$mealArea     = trim($_POST['meal_area']      ?? '');

if ($mealId === '' || $mealName === '') {
    echo json_encode(['success' => false, 'message' => 'Missing recipe data.']);
    exit;
}

try {
    $pdo = getPDO();

    $stmt = $pdo->prepare("
        INSERT IGNORE INTO favorites
            (user_id, meal_id, meal_name, meal_thumb, meal_category, meal_area)
        VALUES
            (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([$userId, $mealId, $mealName, $mealThumb, $mealCategory, $mealArea]);

    echo json_encode(['success' => true, 'message' => 'Recipe saved to favorites!']);

} catch (PDOException $e) {
    error_log("Save favorite error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>