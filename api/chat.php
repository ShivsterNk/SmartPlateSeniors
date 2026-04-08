<?php
/**
 * Chat API Endpoint
 * Handles chat messages from frontend
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display, we'll return JSON errors
ini_set('log_errors', 1);

session_start();

// Set JSON header
header('Content-Type: application/json');
// TEMPORARY DEBUG - remove after fixing
$aiFile = __DIR__ . '/../includes/ai-helper.php';
if (!file_exists($aiFile)) {
    echo json_encode(['error' => 'ai-helper.php not found at: ' . $aiFile]);
    exit;
}
try {
    // Check if files exist before requiring
    $requiredFiles = [
        __DIR__ . '/../config/db.php',
        __DIR__ . '/../includes/ai-helper.php',
        __DIR__ . '/../includes/auth.php'
    ];

    foreach ($requiredFiles as $file) {
        if (!file_exists($file)) {
            throw new Exception("Required file missing: " . basename($file));
        }
    }

    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../includes/ai-helper.php';
    require_once __DIR__ . '/../includes/auth.php';

    // Check authentication
    if (!is_logged_in()) {
        http_response_code(401);
        echo json_encode(['error' => 'Not authenticated']);
        exit;
    }

    $userId = get_user_id();

    // Get request data
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

    $message = $input['message'] ?? '';
    $conversationId = $input['conversation_id'] ?? null;

    if (empty($message)) {
        http_response_code(400);
        echo json_encode(['error' => 'Message is required']);
        exit;
    }

    $pdo = getPDO();

    // Create new conversation if needed
    if (!$conversationId) {
        $stmt = $pdo->prepare("INSERT INTO chat_conversations (user_id) VALUES (?)");
        $stmt->execute([$userId]);
        $conversationId = $pdo->lastInsertId();
    }

    // Save user message
    $stmt = $pdo->prepare("
        INSERT INTO chat_messages (conversation_id, role, content) 
        VALUES (?, 'user', ?)
    ");
    $stmt->execute([$conversationId, $message]);

    // Get conversation history (last 10 messages for context)
    $stmt = $pdo->prepare("
        SELECT role, content 
        FROM chat_messages 
        WHERE conversation_id = ? 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $stmt->execute([$conversationId]);
    $history = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

    // Get user profile data
    $stmt = $pdo->prepare("
        SELECT u.name, up.dietary_restrictions, up.allergies, 
               up.calorie_goal, up.protein_goal, up.carbs_goal, up.fat_goal
        FROM users u
        LEFT JOIN user_preferences up ON u.id = up.user_id
        WHERE u.id = ?
    ");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Build user context
    $userContext = [];
    if ($userData) {
        $userContext['name'] = $userData['name'] ?? '';
        if ($userData['dietary_restrictions']) {
            $userContext['dietary_restrictions'] = $userData['dietary_restrictions'];
        }
        if ($userData['allergies']) {
            $userContext['allergies'] = $userData['allergies'];
        }
        if ($userData['calorie_goal']) {
            $userContext['calorie_goal'] = $userData['calorie_goal'];
        }
        if ($userData['protein_goal']) {
            $userContext['protein_goal'] = $userData['protein_goal'];
        }
        if ($userData['carbs_goal']) {
            $userContext['carbs_goal'] = $userData['carbs_goal'];
        }
        if ($userData['fat_goal']) {
            $userContext['fat_goal'] = $userData['fat_goal'];
        }
    }

    // Call AI
    $ai = new AIHelper();
    $response = $ai->chat($history, $userContext);

    // Save assistant response
    $stmt = $pdo->prepare("
        INSERT INTO chat_messages (conversation_id, role, content) 
        VALUES (?, 'assistant', ?)
    ");
    $stmt->execute([$conversationId, $response]);

    // Return response
    echo json_encode([
        'conversation_id' => $conversationId,
        'response' => $response,
        'success' => true
    ]);

} catch (Exception $e) {
    error_log("Chat API error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage(),
        'file' => basename($e->getFile()),
        'line' => $e->getLine()
    ]);
}