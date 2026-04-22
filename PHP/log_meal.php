<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/api-keys.php';

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
$calories = floatval($input['calories'] ?? 0);
$carbs    = floatval($input['carbs']    ?? 0);
$protein  = floatval($input['protein']  ?? 0);
$fat      = floatval($input['fat']      ?? 0);

if (!$mealType || !$mealName) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing meal info']);
    exit;
}

// ✅ If no nutrition data provided, fetch from USDA
if ($calories == 0) {
    $usdaUrl = 'https://api.nal.usda.gov/fdc/v1/foods/search?api_key=' . FDC_API_KEY
        . '&query=' . urlencode($mealName) . '&pageSize=1';

    $ch = curl_init($usdaUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT        => 10,
    ]);
    $usdaResponse = curl_exec($ch);
    curl_close($ch);

    $usdaData = json_decode($usdaResponse, true);
    $food     = $usdaData['foods'][0] ?? null;

    if ($food) {
        $nutrients = $food['foodNutrients'] ?? [];
        foreach ($nutrients as $n) {
            switch ($n['nutrientName'] ?? '') {
                case 'Energy':
                    $calories = round($n['value'] ?? 0); break;
                case 'Carbohydrate, by difference':
                    $carbs = round($n['value'] ?? 0, 1); break;
                case 'Protein':
                    $protein = round($n['value'] ?? 0, 1); break;
                case 'Total lipids (fat)':
                    $fat = round($n['value'] ?? 0, 1); break;
            }
        }
    }
}

// Check if already logged today for non-manual entries
if ($mealType !== 'Manual') {
    $stmtCheck = $pdo->prepare("
        SELECT id FROM nutrition_logs 
        WHERE user_id = ? AND log_date = ? AND meal_type = ?
    ");
    $stmtCheck->execute([$userId, $logDate, $mealType]);
    if ($stmtCheck->fetch()) {
        echo json_encode(['status' => 'already_logged', 'message' => 'Already logged!']);
        exit;
    }
}

$stmtInsert = $pdo->prepare("
    INSERT INTO nutrition_logs (user_id, log_date, meal_type, food_name, calories, carbs_g, protein_g, fat_g, source)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmtInsert->execute([
    $userId, $logDate, $mealType, $mealName,
    $calories, $carbs, $protein, $fat,
    $mealType === 'Manual' ? 'manual' : 'meal_plan'
]);

$newId = $pdo->lastInsertId();
echo json_encode([
    'status'   => 'success',
    'id'       => $newId,
    'calories' => $calories,
    'message'  => 'Meal logged!'
]);