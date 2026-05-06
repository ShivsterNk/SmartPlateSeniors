// Chat functionality for Smart Plate AI Assistant
console.log('chat.js file loaded!');

let conversationId = null;

const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const chatMessages = document.getElementById('chatMessages');
const sendBtn = document.getElementById('sendBtn');
const sendText = document.getElementById('sendText');
const sendSpinner = document.getElementById('sendSpinner');

// ✅ Scroll to bottom helper
function scrollToBottom() {
    const chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Handle form submission
chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const message = messageInput.value.trim();
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
        const response = await fetch('../api/chat.php', {
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

        if (!response.ok) {
            throw new Error(data.error || 'Failed to get response');
        }

        conversationId = data.conversation_id;
        typingIndicator.remove();
        addMessage(data.response, 'assistant');

    } catch (error) {
        console.error('Chat error:', error);
        typingIndicator.remove();
        addMessage('Sorry, I encountered an error. Please try again.', 'assistant');
    } finally {
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

    let formattedContent = content
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\n\n/g, '</p><p>')
        .replace(/\n/g, '<br>')
        .replace(/- (.*?)(<br>|$)/g, '<li>$1</li>');

    if (formattedContent.includes('<li>')) {
        formattedContent = formattedContent.replace(/(<li>.*<\/li>)+/g, '<ul>$&</ul>');
    }

    contentDiv.innerHTML = `<p>${formattedContent}</p>`;
    messageDiv.appendChild(contentDiv);
    chatMessages.appendChild(messageDiv);

    scrollToBottom();

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
    scrollToBottom();
    return messageDiv;
}

// Allow Enter key to send (Shift+Enter for new line)
messageInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        chatForm.dispatchEvent(new Event('submit'));
    }
}); // ✅ Fixed: missing closing }); was causing "Unexpected end of input"

// Focus input on load
messageInput.focus();

// ✅ Scroll to bottom on initial load
window.addEventListener('load', () => {
    window.scrollTo(0, 0);
    scrollToBottom();
});