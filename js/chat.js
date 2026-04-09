// Chat functionality for Smart Plate AI Assistant
console.log('chat.js file loaded!');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready - initializing PlateBot');

    let conversationId = null;

    const messageInput = document.getElementById('messageInput');
    const chatMessages = document.getElementById('chatMessages');
    const sendBtn = document.getElementById('sendBtn');
    const sendText = document.getElementById('sendText');
    const sendSpinner = document.getElementById('sendSpinner');

    console.log('Elements found:', {
        messageInput: !!messageInput,
        chatMessages: !!chatMessages,
        sendBtn: !!sendBtn
    });

    // Use event delegation - attach to document instead of form
    // This is more reliable when other scripts might interfere
    document.addEventListener('submit', async (e) => {
        // Only handle our chat form
        if (e.target.id !== 'chatForm') return;

        e.preventDefault();
        console.log('Form submitted via delegation!');

        const message = messageInput.value.trim();
        console.log('Message:', message);
        if (!message) return;

        // Add user message to UI
        addMessage(message, 'user');
        messageInput.value = '';

        // Show typing indicator
        const typingIndicator = addTypingIndicator();

        // Disable send button
        sendBtn.disabled = true;
        sendText.classList.add('d-none');
        sendSpinner.classList.remove('d-none');

        try {
            // Send to API
            const response = await fetch('/SmartPlateSeniors/api/chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    conversation_id: conversationId
                })
            });

            const data = await response.json();
            console.log('API response:', data);

            if (!response.ok) {
                throw new Error(data.error || 'Failed to get response');
            }

            // Store conversation ID
            conversationId = data.conversation_id;

            // Remove typing indicator
            typingIndicator.remove();

            // Add assistant response
            addMessage(data.response, 'assistant');

        } catch (error) {
            console.error('Chat error:', error);
            typingIndicator.remove();
            addMessage('Sorry, I encountered an error. Please try again.', 'assistant');
        } finally {
            // Re-enable send button
            sendBtn.disabled = false;
            sendText.classList.remove('d-none');
            sendSpinner.classList.add('d-none');
            messageInput.focus();
        }
    });

    // Add message to chat
    function addMessage(content, role) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${role}-message`;

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';

        // Convert markdown-style formatting to HTML
        let formattedContent = content
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>')
            .replace(/- (.*?)(<br>|$)/g, '<li>$1</li>');

        // Wrap list items in ul tags
        if (formattedContent.includes('<li>')) {
            formattedContent = formattedContent.replace(/(<li>.*<\/li>)+/g, '<ul>$&</ul>');
        }

        contentDiv.innerHTML = `<p>${formattedContent}</p>`;
        messageDiv.appendChild(contentDiv);
        chatMessages.appendChild(messageDiv);

        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;

        return messageDiv;
    }

    // Add typing indicator
    function addTypingIndicator() {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message assistant-message';
        messageDiv.innerHTML = `
            <div class="message-content typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return messageDiv;
    }

    // Allow Enter key to send (Shift+Enter for new line)
    if (messageInput) {
        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const form = document.getElementById('chatForm');
                if (form) {
                    form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                }
            }
        });

        // Focus input on load
        messageInput.focus();
    }

    console.log('PlateBot initialized successfully with event delegation!');

}); // End DOMContentLoaded