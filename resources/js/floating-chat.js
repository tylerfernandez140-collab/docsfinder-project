function toggleChatWindow() {
    const chatWindow = document.getElementById('chat-window');
    const isOpen = chatWindow.style.display === 'flex';
    chatWindow.style.display = isOpen ? 'none' : 'flex';

    if (!isOpen) {
        document.getElementById('notification-dot').style.display = 'none'; // hide notification when opened
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('chat-messages');
    const notificationDot = document.getElementById('notification-dot');

    // ✅ Safe hidden values
    const CURRENT_USER_ID = document.getElementById('current-user-id').value;
    const GROUP_ID = document.getElementById('group-id').value;

    function sendMessage() {
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
          .then(data => {
              input.value = '';
          })
          .catch(error => console.error('Error sending message:', error));
    }

    // Echo listener for new messages
    Echo.private('chat.' + GROUP_ID)
        .listen('MessageSent', (e) => {
            console.log('New message received in floating chat:', e.message);
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.innerHTML = `\n                <strong>${e.message.user.id == CURRENT_USER_ID ? 'You' : e.message.user.name}:</strong>\n                ${e.message.content}\n                <small>${moment(e.message.created_at).format('MMM DD, YYYY HH:mm A')}</small>\n            `;
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            notificationDot.style.display = 'block'; // show notification for new message
        });
});