<?php
session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$pdo    = getPDO();
$userId = (int) $_SESSION['user_id'];

$input = json_decode(file_get_contents('php://input'), true);
$id    = (int) ($input['id'] ?? 0);

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing log ID']);
    exit;
}

// Only delete if it belongs to this user
$stmt = $pdo->prepare("DELETE FROM nutrition_logs WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $userId]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Log not found or not yours']);
}