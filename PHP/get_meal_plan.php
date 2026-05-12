<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');
error_log("=== get_meal_plan.php called ===");
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

// ── Get requested date (default today) ──
$requestedDate = $_GET['date'] ?? date('Y-m-d');

// ── Check if meals already exist for this date ──
$stmt = $pdo->prepare("
    SELECT meal_type, meal_name, description, emoji 
    FROM meal_plans 
    WHERE user_id = ? AND plan_date = ?
    ORDER BY FIELD(meal_type, 'Breakfast', 'Snack', 'Lunch', 'Dinner')
");
$stmt->execute([$userId, $requestedDate]);
$existing = $stmt->fetchAll();

if (count($existing) >= 4) {
    header('Content-Type: application/json');
    echo json_encode(['meals' => $existing, 'source' => 'db']);
    exit;
}

// ── Fetch user preferences ──
$stmtPrefs = $pdo->prepare("SELECT * FROM user_preferences WHERE user_id = ?");
$stmtPrefs->execute([$userId]);
$prefs = $stmtPrefs->fetch() ?: [];

$name = $_SESSION['user_name'] ?? 'there';
$dateLabel = date('l, F j, Y', strtotime($requestedDate));

// ── Build prompt ──
$prefsStr = '';
if (!empty($prefs['dietary_restrictions'])) $prefsStr .= "\n- Dietary restrictions: {$prefs['dietary_restrictions']}";
if (!empty($prefs['allergies']))            $prefsStr .= "\n- Allergies: {$prefs['allergies']}";
if (!empty($prefs['calorie_goal']))         $prefsStr .= "\n- Daily calorie goal: {$prefs['calorie_goal']} kcal";
if (!empty($prefs['protein_goal']))         $prefsStr .= "\n- Protein goal: {$prefs['protein_goal']}g";
if (!empty($prefs['carbs_goal']))           $prefsStr .= "\n- Carbs goal: {$prefs['carbs_goal']}g";
if (!empty($prefs['fat_goal']))             $prefsStr .= "\n- Fat goal: {$prefs['fat_goal']}g";

$systemPrompt = "You are a nutrition assistant. Generate a meal plan for {$dateLabel} for {$name}.\n"
    . (!empty($prefsStr) ? "User preferences:{$prefsStr}\n" : "No preferences set, generate a balanced plan.\n")
    . "IMPORTANT: You MUST include exactly 4 meals: Breakfast, Snack, Lunch, and Dinner. Do not skip Snack even if the user's preference says 3 meals.\n"
    . "You MUST respond with ONLY a valid JSON object. No markdown, no backticks, no explanation, no extra text whatsoever. The response must start with { and end with }\n"
    . "Use exactly this format with ALL 4 meals:\n"
    . "{\"meals\":[{\"meal_type\":\"Breakfast\",\"meal_name\":\"Name\",\"description\":\"One sentence max\",\"emoji\":\"🍳\"},{\"meal_type\":\"Snack\",\"meal_name\":\"Name\",\"description\":\"One sentence max\",\"emoji\":\"🍎\"},{\"meal_type\":\"Lunch\",\"meal_name\":\"Name\",\"description\":\"One sentence max\",\"emoji\":\"🥗\"},{\"meal_type\":\"Dinner\",\"meal_name\":\"Name\",\"description\":\"One sentence max\",\"emoji\":\"🍽️\"}]}";

// ── Call Claude API ──
$ch = curl_init('https://api.anthropic.com/v1/messages');

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'x-api-key: ' . AI_API_KEY,
        'anthropic-version: 2023-06-01'
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'model'      => AI_MODEL,
        'max_tokens' => 800,
        'system'     => $systemPrompt,
        'messages'   => [['role' => 'user', 'content' => 'Generate my meal plan.']]
    ])
]);

$response  = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    error_log("CURL ERROR: " . $curlError);
    echo json_encode(['error' => 'Curl failed: ' . $curlError]);
    exit;
}
error_log("RAW RESPONSE: " . $response);

$data    = json_decode($response, true);
error_log("DECODED DATA: " . print_r($data, true));
$rawText = $data['content'][0]['text'] ?? '';
error_log("RAW TEXT: " . $rawText);

if (empty($rawText)) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not generate meal plan']);
    exit;
}

$text     = preg_replace('/```json|```/', '', trim($rawText));
$mealData = json_decode($text, true);

if (empty($mealData['meals'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not generate meal plan']);
    exit;
}

// ── Save to DB ──
$stmtInsert = $pdo->prepare("
    INSERT IGNORE INTO meal_plans (user_id, plan_date, meal_type, meal_name, description, emoji)
    VALUES (?, ?, ?, ?, ?, ?)
");

foreach ($mealData['meals'] as $meal) {
    $stmtInsert->execute([
        $userId,
        $requestedDate,
        $meal['meal_type'],
        $meal['meal_name'],
        $meal['description'],
        $meal['emoji']
    ]);
    if ($stmtInsert->rowCount() === 0) {
        error_log("INSERT IGNORE skipped: " . json_encode($meal));
    }
}

// ── Always return from DB after saving so we get consistent ordering ──
$stmtFinal = $pdo->prepare("
    SELECT meal_type, meal_name, description, emoji 
    FROM meal_plans 
    WHERE user_id = ? AND plan_date = ?
    ORDER BY FIELD(meal_type, 'Breakfast', 'Snack', 'Lunch', 'Dinner')
");
$stmtFinal->execute([$userId, $requestedDate]);
$saved = $stmtFinal->fetchAll();

header('Content-Type: application/json');
echo json_encode(['meals' => $saved, 'source' => 'ai']);