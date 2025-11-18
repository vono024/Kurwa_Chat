<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Чат з {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex flex-col bg-gray-100">
<header class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-4 shadow-lg flex items-center gap-4">
    <a href="{{ route('chats.index') }}" class="text-2xl hover:scale-110 transition">←</a>
    <div onclick="showProfile()" class="w-11 h-11 rounded-full bg-white/30 flex items-center justify-center font-bold text-lg cursor-pointer overflow-hidden flex-shrink-0">
        @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
        @else
            {{ strtoupper(substr($user->name, 0, 1)) }}
        @endif
    </div>
    <div onclick="showProfile()" class="flex-1 min-w-0 cursor-pointer">
        <h2 class="font-bold text-lg truncate">{{ $user->name }}</h2>
        <p class="text-sm text-white/80 truncate">{{ $user->phone }}</p>
    </div>
</header>

<div id="profileModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Профіль</h3>
            <button onclick="closeModal()" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <div class="text-center">
            <div id="modalAvatar" class="w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4 overflow-hidden"></div>
            <h4 id="modalName" class="text-xl font-semibold mb-2"></h4>
            <p id="modalPhone" class="text-gray-600 mb-4"></p>
            <div id="modalBio" class="bg-gray-100 p-4 rounded-lg text-sm text-gray-700 mb-4"></div>
            <p id="modalDate" class="text-xs text-gray-500"></p>
        </div>
    </div>
</div>

<div id="messagesContainer" class="flex-1 overflow-y-auto p-4 bg-[#e5ddd5]">
    <div id="messagesList">
        @if($messages->count() > 0)
            @foreach($messages as $message)
                <div class="mb-4 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}" data-id="{{ $message->id }}">
                    <div class="inline-block max-w-[70%] px-4 py-2 rounded-2xl {{ $message->sender_id === auth()->id() ? 'bg-[#dcf8c6] rounded-br-sm' : 'bg-white rounded-bl-sm' }} shadow">
                        <p class="break-words">{{ $message->content }}</p>
                        <p class="text-xs text-gray-500 mt-1 message-time" data-timestamp="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->format('H:i') }}</p>
                    </div>
                </div>
            @endforeach
        @else
            <div id="noMessages" class="text-center py-20 text-gray-500">
                <h3 class="text-xl font-semibold mb-2">Це початок розмови</h3>
                <p>Відправте перше повідомлення!</p>
            </div>
        @endif
    </div>
</div>

<form method="POST" action="{{ route('message.send', $user) }}" id="messageForm" class="bg-white p-4 border-t flex gap-3">
    @csrf
    <input type="text" name="content" id="messageInput" placeholder="Напишіть повідомлення..." required autocomplete="off"
           class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-full focus:outline-none focus:border-purple-500">
    <button type="submit" id="sendBtn"
            class="px-6 py-3 bg-purple-600 text-white rounded-full font-semibold hover:bg-purple-700 transition">
        Відправити
    </button>
</form>

<script>
    const userId = {{ $user->id }};
    const authUserId = {{ auth()->id() }};
    const messagesContainer = document.getElementById('messagesContainer');
    const messagesList = document.getElementById('messagesList');
    const messageInput = document.getElementById('messageInput');
    const noMessages = document.getElementById('noMessages');

    let lastMessageId = {{ $messages->last()->id ?? 0 }};
    let isAtBottom = true;

    messagesContainer.addEventListener('scroll', () => {
        isAtBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight < 100;
    });

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    scrollToBottom();

    function formatTime(dateString) {
        const date = new Date(dateString);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.querySelectorAll('.message-time').forEach(el => {
        const timestamp = el.getAttribute('data-timestamp');
        if (timestamp) {
            el.textContent = formatTime(timestamp);
        }
    });

    function addMessage(message) {
        if (noMessages) noMessages.remove();

        const isSent = message.sender_id === authUserId;
        const messageDiv = document.createElement('div');
        messageDiv.className = `mb-4 ${isSent ? 'text-right' : 'text-left'}`;
        messageDiv.setAttribute('data-id', message.id);

        const timeString = formatTime(message.created_at);

        messageDiv.innerHTML = `
            <div class="inline-block max-w-[70%] px-4 py-2 rounded-2xl ${isSent ? 'bg-[#dcf8c6] rounded-br-sm' : 'bg-white rounded-bl-sm'} shadow">
                <p class="break-words">${escapeHtml(message.content)}</p>
                <p class="text-xs text-gray-500 mt-1">${timeString}</p>
            </div>
        `;

        messagesList.appendChild(messageDiv);
        if (isAtBottom) scrollToBottom();
    }

    async function loadNewMessages() {
        try {
            const response = await fetch(`/chat/${userId}/messages`);
            const data = await response.json();

            data.messages.forEach(message => {
                const existingMessage = document.querySelector(`[data-id="${message.id}"]`);
                if (!existingMessage && message.id > lastMessageId) {
                    addMessage(message);
                    lastMessageId = message.id;
                }
            });
        } catch (error) {
            console.error('Помилка завантаження:', error);
        }
    }

    setInterval(loadNewMessages, 2000);

    async function showProfile() {
        try {
            const response = await fetch(`/profile/{{ $user->id }}/view`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();

            const avatarDiv = document.getElementById('modalAvatar');
            if (data.avatar) {
                avatarDiv.innerHTML = `<img src="${data.avatar}" class="w-full h-full object-cover">`;
            } else {
                avatarDiv.textContent = data.name.charAt(0).toUpperCase();
            }

            document.getElementById('modalName').textContent = data.name;
            document.getElementById('modalPhone').textContent = data.phone;
            document.getElementById('modalBio').textContent = data.bio || 'Інформація відсутня';
            document.getElementById('modalDate').textContent = `Користувач з ${data.created_at}`;

            document.getElementById('profileModal').classList.remove('hidden');
        } catch (error) {
            console.error('Помилка:', error);
        }
    }

    function closeModal() {
        document.getElementById('profileModal').classList.add('hidden');
    }

    document.getElementById('profileModal').addEventListener('click', (e) => {
        if (e.target.id === 'profileModal') {
            closeModal();
        }
    });

    messageInput.focus();
</script>
</body>
</html>
