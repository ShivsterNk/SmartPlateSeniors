<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_login();

// Include header
include('../includes/header.php');
?>

    <style>
        /* PlateBot specific styles */
        .platebot-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
            padding-top: calc(70px + 10px); /* ✅ 70px navbar + 20px breathing room */

        }

        .platebot-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .platebot-header {
            background: #283618;
            color: white;
            padding: 24px 28px;
            text-align: center;
        }

        .platebot-header h1 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 600;
        }

        .platebot-header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .chat-container {
            height: 500px;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 0;
        }

        .chat-messages {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding: 1.5rem;
            min-height: 500px;
        }

        .message {
            max-width: 75%;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-message {
            align-self: flex-end;
            margin-left: auto;
        }

        .user-message .message-content {
            background: #283618;
            color: white;
            padding: 0.875rem 1.25rem;
            border-radius: 18px 18px 4px 18px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .assistant-message {
            align-self: flex-start;
        }

        .assistant-message .message-content {
            background: white;
            border: 1px solid #dee2e6;
            padding: 0.875rem 1.25rem;
            border-radius: 18px 18px 18px 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .message-content p {
            margin-bottom: 0.75rem;
            line-height: 1.6;
        }

        .message-content p:last-child {
            margin-bottom: 0;
        }

        .message-content ul, .message-content ol {
            margin-bottom: 0.75rem;
            padding-left: 1.5rem;
        }

        .message-content li {
            margin-bottom: 0.25rem;
        }

        .message-content strong {
            font-weight: 600;
        }

        .typing-indicator {
            display: flex;
            gap: 0.4rem;
            padding: 0.875rem 1.25rem;
            align-items: center;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background: #6c757d;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.7;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        .chat-footer {
            background: white;
            border-top: 1px solid #dee2e6;
            padding: 20px 24px;
        }

        .chat-form {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
        }

        .chat-input {
            flex: 1;
            padding: 12px 16px;
            border: 1.5px solid #ced4da;
            border-radius: 24px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }

        .chat-input:focus {
            border-color: #283618;
        }

        .send-button {
            background: #283618;
            color: white;
            border: none;
            border-radius: 24px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            min-width: 90px;
        }

        .send-button:hover {
            background: #3a4f22;
        }

        .send-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .chat-tip {
            text-align: center;
            color: #6c757d;
            font-size: 13px;
        }

        .disclaimer {
            text-align: center;
            color: #6c757d;
            font-size: 13px;
            margin: 20px auto;
            max-width: 700px;
        }

        .spinner-border-sm {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .d-none {
            display: none !important;
        }

        .chat-messages::-webkit-scrollbar {
            width: 8px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        @media (max-width: 768px) {
            .message {
                max-width: 85%;
            }

            .chat-container {
                height: 400px;
            }

            .chat-messages {
                min-height: 400px;
            }
        }
    </style>

    <main class="platebot-container">
        <div class="platebot-card">
            <div class="platebot-header">
                <h1>PlateBot</h1>
                <p>Your AI nutrition companion for meal planning and healthy eating!</p>
            </div>

            <div class="chat-container">
                <div class="chat-messages" id="chatMessages">
                    <div class="message assistant-message">
                        <div class="message-content">
                            <p>Hi <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'there'); ?>! 👋</p>
                            <p>I'm PlateBot, your AI nutrition companion. I can help you with:</p>
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

            <div class="chat-footer">
                <form id="chatForm" class="chat-form">
                    <input
                            type="text"
                            id="messageInput"
                            class="chat-input"
                            placeholder="Ask me anything about nutrition..."
                            autocomplete="off"
                            required
                    >
                    <button type="submit" class="send-button" id="sendBtn">
                        <span id="sendText">Send</span>
                        <span id="sendSpinner" class="spinner-border-sm d-none"></span>
                    </button>
                </form>
                <div class="chat-tip">
                    💡 Tip: I know about your dietary preferences and goals!
                </div>
            </div>
        </div>

        <p class="disclaimer">
            AI responses are for informational purposes only. Consult a healthcare professional for medical advice.
        </p>
    </main>

    <script src="../js/chat.js"></script>

<?php
// Include footer
include('../includes/footer.php');
?>