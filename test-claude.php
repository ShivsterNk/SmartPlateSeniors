<?php
require_once __DIR__ . '/config/api-keys.php';

$apiKey = AI_API_KEY;

$data = [
    'model' => 'claude-sonnet-4-6',  // ✅ Updated model name
    'max_tokens' => 100,
    'messages' => [
        [
            'role' => 'user',
            'content' => 'Say hello and tell me you are working!'
        ]
    ]
];

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-api-key: ' . $apiKey,
    'anthropic-version: 2023-06-01'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h2>Claude API Test</h2>";
echo "<p><strong>HTTP Code:</strong> $httpCode</p>";

if ($httpCode === 200) {
    echo "<p style='color: green; font-size: 20px;'><strong>✅ SUCCESS!</strong></p>";
    $result = json_decode($response, true);
    echo "<p><strong>Claude says:</strong></p>";
    echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 8px; font-size: 16px;'>";
    echo htmlspecialchars($result['content'][0]['text']);
    echo "</div>";

    echo "<br><p><strong>Full Response (for debugging):</strong></p>";
    echo "<pre style='background: #f9f9f9; padding: 10px; overflow-x: auto;'>" . json_encode($result, JSON_PRETTY_PRINT) . "</pre>";
} else {
    echo "<p style='color: red; font-size: 20px;'><strong>❌ ERROR</strong></p>";
    echo "<pre style='background: #ffe6e6; padding: 10px;'>$response</pre>";
}