<?php
/**
 * AI Helper Class for Smart Plate
 * Handles communication with Claude API
 */

require_once __DIR__ . '/../config/api-keys.example.php';

class AIHelper {

    private $apiKey;
    private $model;

    public function __construct() {
        $this->apiKey = AI_API_KEY;
        $this->model = AI_MODEL ?? 'claude-sonnet-4-6';
    }

    /**
     * Send message to AI and get response
     *
     * @param array $messages - Array of messages with 'role' and 'content'
     * @param array $userData - User profile data for personalization
     * @return string - AI response
     */
    public function chat($messages, $userData = []) {
        // Build system prompt with user data
        $systemPrompt = $this->buildSystemPrompt($userData);

        // Separate system message from conversation
        $conversationMessages = [];
        foreach ($messages as $msg) {
            if ($msg['role'] !== 'system') {
                $conversationMessages[] = $msg;
            }
        }

        return $this->callClaude($conversationMessages, $systemPrompt);
    }

    /**
     * Build system prompt with user context
     */
    private function buildSystemPrompt($userData) {
        $prompt = "You are PlateBot, a friendly and knowledgeable nutrition assistant for Smart Plate, a meal planning and nutrition tracking app. ";
        $prompt .= "You help users make healthier food decisions, create meal plans, and answer nutrition questions.\n\n";

        // Add user-specific context
        if (!empty($userData)) {
            $prompt .= "USER PROFILE:\n";

            if (!empty($userData['name'])) {
                $prompt .= "- Name: " . $userData['name'] . "\n";
            }

            if (!empty($userData['dietary_restrictions'])) {
                $prompt .= "- Dietary Restrictions: " . $userData['dietary_restrictions'] . "\n";
            }

            if (!empty($userData['allergies'])) {
                $prompt .= "- Allergies: " . $userData['allergies'] . "\n";
            }

            if (!empty($userData['calorie_goal'])) {
                $prompt .= "- Daily Calorie Goal: " . $userData['calorie_goal'] . " calories\n";
            }

            if (!empty($userData['protein_goal']) || !empty($userData['carbs_goal']) || !empty($userData['fat_goal'])) {
                $prompt .= "- Macro Goals: ";
                $macros = [];
                if (!empty($userData['protein_goal'])) $macros[] = $userData['protein_goal'] . "g protein";
                if (!empty($userData['carbs_goal'])) $macros[] = $userData['carbs_goal'] . "g carbs";
                if (!empty($userData['fat_goal'])) $macros[] = $userData['fat_goal'] . "g fat";
                $prompt .= implode(", ", $macros) . "\n";
            }

            $prompt .= "\n";
        }

        $prompt .= "GUIDELINES:\n";
        $prompt .= "- Provide evidence-based nutrition advice\n";
        $prompt .= "- Be encouraging and supportive\n";
        $prompt .= "- Consider the user's dietary restrictions and allergies\n";
        $prompt .= "- When creating meal plans, ensure they align with the user's calorie and macro goals\n";
        $prompt .= "- Keep responses concise and friendly\n";
        $prompt .= "- Use emojis occasionally to be warm and approachable\n";
        $prompt .= "- Always prioritize user safety - recommend consulting healthcare professionals for medical advice\n";

        return $prompt;
    }

    /**
     * Call Claude API
     */
    private function callClaude($messages, $systemMessage) {
        $data = [
            'model' => $this->model,
            'max_tokens' => 1024,
            'system' => $systemMessage,
            'messages' => $messages
        ];

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-api-key: ' . $this->apiKey,
            'anthropic-version: 2023-06-01'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            error_log("Claude API Error: " . $response);
            throw new Exception("AI service temporarily unavailable. Please try again.");
        }

        $result = json_decode($response, true);
        return $result['content'][0]['text'] ?? '';
    }
}