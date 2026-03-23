<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Nutrition Assistant - Smart Plate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/chat.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">🤖 AI Nutrition Assistant</h4>
                        <small>Ask me anything about nutrition, meal planning, or food!</small>
                    </div>
                    
                    <div class="card-body chat-container" id="chatContainer">
                        <div class="chat-messages" id="chatMessages">
                            <div class="message assistant-message">
                                <div class="message-content">
                                    <p>Hi <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'there'); ?>! 👋</p>
                                    <p>I'm your AI nutrition assistant. I can help you with:</p>
                                    <ul>
                                        <li>Creating personalized meal plans</li>
                                        <li>Answering nutrition questions</li>
                                        <li>Food recommendations based on your goals</li>
                                        <li>Recipe suggestions</li>
                                        <li>Dietary advice</li>
                                    </ul>
                                    <p>What would you like to know?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <form id="chatForm" class="d-flex gap-2">
                            <input 
                                type="text" 
                                id="messageInput" 
                                class="form-control" 
                                placeholder="Ask me anything about nutrition..."
                                autocomplete="off"
                                required
                            >
                            <button type="submit" class="btn btn-success" id="sendBtn">
                                <span id="sendText">Send</span>
                                <span id="sendSpinner" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </form>
                        <small class="text-muted d-block mt-2">
                            💡 Tip: I know about your dietary preferences and goals!
                        </small>
                    </div>
                </div>
                
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        AI responses are for informational purposes only. Consult a healthcare professional for medical advice.
                    </small>
                </div>
                
                <div class="mt-3 text-center">
                    <a href="../index.html" class="btn btn-outline-secondary">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../js/chat.js"></script>
</body>
</html>
