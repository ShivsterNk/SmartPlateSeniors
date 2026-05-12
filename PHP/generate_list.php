<?php
require_once('../config/api-keys.php');

$data = json_decode(file_get_contents("php://input"), true);
$ingredients = $data['ingredients'] ?? [];

$ingredientText = implode(", ", $ingredients);

// Claude prompt
$prompt = "Generate a clean, organized shopping list based on these ingredients: $ingredientText. Group similar items and remove duplicates.";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.anthropic.com/v1/messages");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "x-api-key: " . AI_API_KEY,
    "anthropic-version: 2023-06-01"
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => AI_MODEL,
    "max_tokens" => 300,
    "messages" => [
        [
            "role" => "user",
            "content" => $prompt
        ]
    ]
]));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

// Extract Claude response
$list = $result['content'][0]['text'] ?? "Error generating list";

echo json_encode(["list" => $list]);