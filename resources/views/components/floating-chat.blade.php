<div id="floating-chat">
    <!-- Floating Button -->
    <div class="floating-button" onclick="toggleChatWindow()">
        <div class="psu-chat-logo">
            <i class="fas fa-comment-dots"></i>
            <span class="notification-dot" id="notification-dot"></span>
        </div>
    </div>

    <!-- Chat Window -->
    <div id="chat-window" class="chat-window">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-left">
                <img src="{{ asset('images/chat-logo.png') }}" alt="PSU Chat Logo" class="chat-header-logo">
                <div>
                    <h6 class="m-0 fw-bold">PSU QA Office</h6>
                    <small>Lingayen Campus</small>
                </div>
            </div>
            <button class="btn-close" onclick="toggleChatWindow()">&times;</button>
        </div>

        <!-- Messages -->
        <div id="chat-messages" class="chat-messages"></div>

        <!-- Input -->
        <div class="chat-input">
            <input type="text" id="chat-message-input" class="form-control" placeholder="Type your message..." />
            <button class="btn-send" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<!-- Hidden values for JS -->
<input type="hidden" id="current-user-id" value="{{ auth()->id() }}">
<input type="hidden" id="group-id" value="{{ $group->id ?? 1 }}">


<style>
/* Floating button */
.floating-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.psu-chat-logo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: royalblue;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    position: relative;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    font-size: 22px;
}

.notification-dot {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 12px;
    height: 12px;
    background: goldenrod;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
    display: none; /* hidden by default */
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(1.6); opacity: 0; }
}

/* Chat window */
.chat-window {
    display: none;
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    flex-direction: column;
    overflow: hidden;
    z-index: 9999;
}

.chat-header {
    background: royalblue;
    color: white;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chat-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chat-header-logo {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
}

.btn-close {
    background: transparent;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

.chat-messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f9f9f9;
}

.message {
    background: #e9ecef;
    padding: 8px 12px;
    border-radius: 10px;
    margin-bottom: 8px;
    max-width: 80%;
    word-wrap: break-word;
}

.message small {
    display: block;
    font-size: 11px;
    opacity: 0.8;
    margin-top: 3px;
}

.chat-input {
    display: flex;
    gap: 5px;
    padding: 10px;
    border-top: 1px solid #ddd;
}

.chat-input input {
    flex: 1;
}

.btn-send {
    background: royalblue;
    color: white;
    border: none;
    padding: 0 15px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.btn-send:hover {
    background: goldenrod;
    color: #000;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('chat-messages');
    const notificationDot = document.getElementById('notification-dot');

    const CURRENT_USER_ID = document.getElementById('current-user-id').value;
    const GROUP_ID = document.getElementById('group-id').value;

    // Toggle chat window
    window.toggleChatWindow = function() {
        const chatWindow = document.getElementById('chat-window');
        const isOpen = chatWindow.style.display === 'flex';
        chatWindow.style.display = isOpen ? 'none' : 'flex';

        if (!isOpen) {
            notificationDot.style.display = 'none';
        }
    }

    // Send message
    window.sendMessage = function() {
        const input = document.getElementById('chat-message-input');
        const text = input.value.trim();
        if (!text) return;

        fetch(`/chat/group/${GROUP_ID}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: text })
        }).then(res => res.json())
          .then(() => {
              input.value = '';
          }).catch(err => console.error(err));
    }

    // Real-time listener with Echo
    if (typeof Echo !== 'undefined') {
        Echo.private(`chat.${GROUP_ID}`)
           .listen('MessageSent', (e) => {
    const div = document.createElement('div');
    div.className = 'message';
    div.innerHTML = `<p>${e.message.content}</p>
                     <small>${e.message.user.id == CURRENT_USER_ID ? 'You' : e.message.user.name} • ${new Date(e.message.created_at).toLocaleString()}</small>`;
    messagesContainer.appendChild(div);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    if (document.getElementById('chat-window').style.display !== 'flex') {
        notificationDot.style.display = 'block';
    }
});
    } else {
        console.error('❌ Echo is not defined. Make sure app.js is loaded in your layout.');
    }

    // Expose functions globally
    window.toggleChatWindow = toggleChatWindow;
    window.sendMessage = sendMessage;
});
</script>
