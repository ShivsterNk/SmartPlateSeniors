<?php
// PHP/api_recipe_generator.php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$ingredient = trim($_POST['ingredient'] ?? '');

if ($ingredient === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Ingredient is required.']);
    exit;
}

// TheMealDB filter-by-ingredient endpoint
$baseUrl = 'https://www.themealdb.com/api/json/v1/1/filter.php';
$url = $baseUrl . '?' . http_build_query(['i' => $ingredient]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 🔽 IMPORTANT: Fix SSL issue on localhost (DO NOT use this in real production)
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'Request error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

if ($httpCode < 200 || $httpCode >= 300) {
    http_response_code($httpCode);
    echo json_encode(['error' => 'API returned status ' . $httpCode]);
    exit;
}

$data = json_decode($response, true);
$meals = $data['meals'] ?? [];

echo json_encode(['meals' => $meals]);