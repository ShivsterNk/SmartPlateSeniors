<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include('../config/db.php');

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$unchecked = $input['unchecked'] ?? [];

try {
    $stmt = getPDO()->prepare("
        UPDATE shopping_lists SET unchecked_items = ? WHERE user_id = ?
    ");
    $stmt->execute([json_encode($unchecked), $user_id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}
