@extends('adminlte::page')

@section('title', 'Chat Group: ' . $group->name)

@section('content')
@vite(['resources/js/app.js'])
<div class="container">
    <h3>Group: {{ $group->name }}</h3>

    {{-- Main Chat --}}
    <div class="chat-box border rounded p-3 mb-4 bg-light" style="height: 400px; overflow-y: auto;" id="chat-messages-container">
        @foreach($messages as $message)
            <div class="mb-3" data-message-id="{{ $message->id }}">
                <div>
                    <strong>{{ $message->user->id === $user->id ? 'You' : $message->user->name }}</strong>
                    <span class="badge bg-secondary">{{ roleName($message->user->role) }}</span>
                    <span class="text-muted small">{{ $message->created_at->format('M d, Y H:i A') }}</span>
                    @if(!$message->readers->contains($user))
                        <span class="badge bg-warning text-dark">Unread</span>
                    @endif
                </div>
                <div>
                    @if($message->type === 'file' && $message->file_path)
                        @if(Str::startsWith($message->mime_type, 'image/'))
                            <img src="{{ Storage::url($message->file_path) }}" class="img-fluid rounded" style="max-height: 200px;" alt="Attachment">
                        @else
                            <i class="bi bi-file-earmark"></i> <a href="{{ Storage::url($message->file_path) }}" target="_blank">{{ basename($message->file_path) }}</a>
                        @endif
                    @else
                        {{ $message->content }}
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Floating Chat --}}
    <div class="chat-box border rounded p-3 mb-4 bg-light floating-chat" style="height: 300px; overflow-y: auto;" id="floating-chat-messages"></div>

    {{-- Main Message Form --}}
    <form action="{{ route('chat.send', $group) }}" method="POST" id="main-message-form" enctype="multipart/form-data">
        @csrf
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type your message..." id="main-chat-input" data-users-route="{{ route('chat.users') }}">
            <button type="button" class="btn btn-outline-secondary" id="emoji-button"><i class="bi bi-emoji-smile"></i></button>
            <emoji-picker class="light" id="emoji-picker"></emoji-picker>
            <input type="file" name="file" class="form-control-file ms-2" id="main-chat-file-input">
            <button class="btn btn-primary">Send</button>
        </div>
    </form>
</div>

@include('components.floating-chat')

{{-- Dependencies --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/at.js/dist/css/jquery.atwho.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/caret.js@0.3.1/dist/jquery.caret.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/at.js/dist/js/jquery.atwho.min.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

{{-- Real-time Chat Script --}}
@vite(['resources/js/app.js'])
<script>
function roleName(roleId) {
    switch (roleId) {
        case 0: return 'User';
        case 1: return 'Admin';
        case 2: return 'Campus DCC';
        case 3: return 'Process Owner';
        default: return 'Unknown';
    }
}

const CURRENT_USER_ID = {{ auth()->user()->id }};

function appendMessageToChat(msg, containerId) {
    const messagesDiv = document.getElementById(containerId);
    if (!messagesDiv) return;

    const newMessageDiv = document.createElement('div');
    newMessageDiv.classList.add('mb-3');
    newMessageDiv.setAttribute('data-message-id', msg.id);

    newMessageDiv.innerHTML = `
        <div>
            <strong>${msg.user.id === CURRENT_USER_ID ? 'You' : msg.user.name}</strong>
            <span class="badge bg-secondary">${roleName(msg.user.role)}</span>
            <span class="text-muted small">${moment(msg.created_at).format('MMM DD, YYYY HH:mm A')}</span>
            ${msg.user.id !== CURRENT_USER_ID ? '<span class="badge bg-warning text-dark">Unread</span>' : ''}
        </div>
        <div>
            ${msg.type === 'file' && msg.file_path
                ? (msg.mime_type && msg.mime_type.startsWith('image/')
                    ? `<img src="/storage/${msg.file_path}" class="img-fluid rounded" style="max-height:200px;" alt="Attachment">`
                    : `<i class="bi bi-file-earmark"></i> <a href="/storage/${msg.file_path}" target="_blank">${msg.file_path.split('/').pop()}</a>`)
                : msg.content}
        </div>
    `;
    messagesDiv.appendChild(newMessageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

document.addEventListener('DOMContentLoaded', () => {
    const groupId = {{ $group->id }};
    const mainContainer = 'chat-messages-container';
    const floatingContainer = 'floating-chat-messages';

    // Scroll main chat to bottom on load
    document.getElementById(mainContainer).scrollTop = document.getElementById(mainContainer).scrollHeight;

    // Listen to real-time events
    Echo.private(`chat.${groupId}`)
        .listen('MessageSent', (e) => {
            const msg = e.message;
            appendMessageToChat(msg, mainContainer);
            appendMessageToChat(msg, floatingContainer);
        });

    // Handle message form submission
    const form = document.getElementById('main-message-form');
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const messageInput = document.getElementById('main-chat-input');
        const fileInput = document.getElementById('main-chat-file-input');

        if (messageInput.value.trim() === '' && fileInput.files.length === 0) return;

        const formData = new FormData();
        formData.append('message', messageInput.value);
        if (fileInput.files[0]) formData.append('file', fileInput.files[0]);

        axios.post('{{ route('chat.send', $group) }}', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        .then(response => {
            const msg = response.data.message;
            appendMessageToChat(msg, mainContainer);
            appendMessageToChat(msg, floatingContainer);

            messageInput.value = '';
            fileInput.value = '';
        })
        .catch(err => console.error('Error sending message:', err));
    });
});
</script>

@vite(['resources/js/app.js'])
<script>
function roleName(roleId) {
    switch (roleId) {
        case 0: return 'User';
        case 1: return 'Admin';
        case 2: return 'Campus DCC';
        case 3: return 'Process Owner';
        default: return 'Unknown';
    }
}

const CURRENT_USER_ID = {{ auth()->user()->id }};

function appendMessageToChat(msg, containerId) {
    const messagesDiv = document.getElementById(containerId);
    if (!messagesDiv) return;

    const newMessageDiv = document.createElement('div');
    newMessageDiv.classList.add('mb-3');
    newMessageDiv.setAttribute('data-message-id', msg.id);

    newMessageDiv.innerHTML = `
        <div>
            <strong>${msg.user.id === CURRENT_USER_ID ? 'You' : msg.user.name}</strong>
            <span class="badge bg-secondary">${roleName(msg.user.role)}</span>
            <span class="text-muted small">${moment(msg.created_at).format('MMM DD, YYYY HH:mm A')}</span>
            ${msg.user.id !== CURRENT_USER_ID ? '<span class="badge bg-warning text-dark">Unread</span>' : ''}
        </div>
        <div>
            ${msg.type === 'file' && msg.file_path
                ? (msg.mime_type && msg.mime_type.startsWith('image/')
                    ? `<img src="/storage/${msg.file_path}" class="img-fluid rounded" style="max-height:200px;" alt="Attachment">`
                    : `<i class="bi bi-file-earmark"></i> <a href="/storage/${msg.file_path}" target="_blank">${msg.file_path.split('/').pop()}</a>`)
                : msg.content}
        </div>
    `;
    messagesDiv.appendChild(newMessageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

document.addEventListener('DOMContentLoaded', () => {
    const groupId = {{ $group->id }};
    const mainContainer = 'chat-messages-container';
    const floatingContainer = 'floating-chat-messages';

    // Scroll main chat to bottom on load
    document.getElementById(mainContainer).scrollTop = document.getElementById(mainContainer).scrollHeight;

    // Listen to real-time events
    Echo.private(`chat.${groupId}`)
        .listen('MessageSent', (e) => {
            
            const msg = e.message;
            appendMessageToChat(msg, mainContainer);
            appendMessageToChat(msg, floatingContainer);
        });

    // Handle message form submission
    const form = document.getElementById('main-message-form');
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const messageInput = document.getElementById('main-chat-input');
        const fileInput = document.getElementById('main-chat-file-input');

        if (messageInput.value.trim() === '' && fileInput.files.length === 0) return;

        const formData = new FormData();
        formData.append('message', messageInput.value);
        if (fileInput.files[0]) formData.append('file', fileInput.files[0]);

        axios.post('{{ route('chat.send', $group) }}', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        .then(response => {
            const msg = response.data.message;
            appendMessageToChat(msg, mainContainer);
            appendMessageToChat(msg, floatingContainer);

            messageInput.value = '';
            fileInput.value = '';
        })
        .catch(err => console.error('Error sending message:', err));
    });
});
</script>

@endsection

@php
function roleName($role) {
    return [
        0 => 'User',
        1 => 'Admin',
        2 => 'Campus DCC',
        3 => 'Process Owner',
    ][$role] ?? 'Unknown';
}
@endphp
