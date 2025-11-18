<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurwa Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col">
<header class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl md:text-2xl font-bold">💬 Kurwa Chat</h1>
        <div class="hidden md:flex gap-3 items-center">
            <span class="font-medium">{{ auth()->user()->name }}</span>
            <a href="{{ route('users.search') }}" class="px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">🔍 Пошук</a>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">Профіль</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">Війти</button>
            </form>
        </div>
        <button onclick="toggleMenu()" class="md:hidden p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
    <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
        <a href="{{ route('users.search') }}" class="block px-4 py-2 bg-white/20 rounded-lg">🔍 Пошук</a>
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 bg-white/20 rounded-lg">Профіль</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 bg-white/20 rounded-lg">Вийти</button>
        </form>
    </div>
</header>

<div class="flex-1 overflow-hidden">
    <div id="chatsList" class="h-full max-w-5xl mx-auto bg-white shadow-xl overflow-y-auto">
        @if(isset($conversations) && $conversations->count() > 0)
            @foreach($conversations as $userId => $messages)
                @php
                    $lastMessage = $messages->last();
                    $otherUser = $lastMessage->sender_id === auth()->id() ? $lastMessage->receiver : $lastMessage->sender;
                    $unreadCount = $messages->where('sender_id', $otherUser->id)->where('is_read', false)->count();
                @endphp
                <a href="{{ route('chat.show', $otherUser) }}" class="chat-item flex items-center gap-4 p-4 border-b hover:bg-gray-50 transition" data-user-id="{{ $otherUser->id }}">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-xl flex-shrink-0 overflow-hidden">
                        @if($otherUser->avatar)
                            <img src="{{ asset('storage/' . $otherUser->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                        @else
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 truncate chat-name">{{ $otherUser->name }}</h3>
                        <p class="text-sm text-gray-600 truncate chat-last-message">
                            {{ $lastMessage->sender_id === auth()->id() ? 'Ви: ' : '' }}
                            {{ Str::limit($lastMessage->content, 50) }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs text-gray-500 chat-time">{{ $lastMessage->created_at->format('H:i') }}</p>
                        @if($unreadCount > 0)
                            <span class="inline-block mt-1 px-2 py-0.5 bg-purple-600 text-white text-xs rounded-full chat-unread">{{ $unreadCount }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        @else
            <div id="noChats" class="text-center py-20 px-6">
                <h3 class="text-2xl font-semibold text-gray-700 mb-4">Немає активних чатів</h3>
                <p class="text-gray-500 mb-8">Знайдіть користувачів і почніть спілкуватися!</p>
                <a href="{{ route('users.search') }}" class="inline-block px-8 py-3 bg-purple-600 text-white rounded-full font-semibold hover:bg-purple-700 transition transform hover:-translate-y-0.5">
                    Знайти користувачів
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    const authUserId = {{ auth()->id() }};

    function toggleMenu() {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    }

    function formatTime(dateString) {
        const date = new Date(dateString);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    async function loadChats() {
        try {
            const response = await fetch('/api/chats');
            const data = await response.json();

            if (data.conversations && data.conversations.length > 0) {
                updateChatsList(data.conversations);
            }
        } catch (error) {
            console.error('Помилка завантаження чатів:', error);
        }
    }

    function updateChatsList(conversations) {
        const chatsList = document.getElementById('chatsList');
        const noChats = document.getElementById('noChats');

        if (noChats) {
            noChats.remove();
        }

        conversations.forEach(conv => {
            const existingChat = document.querySelector(`[data-user-id="${conv.other_user.id}"]`);

            if (existingChat) {
                const lastMessageEl = existingChat.querySelector('.chat-last-message');
                const timeEl = existingChat.querySelector('.chat-time');
                const unreadEl = existingChat.querySelector('.chat-unread');

                lastMessageEl.innerHTML = `${conv.is_sent ? 'Ви: ' : ''}${conv.last_message.substring(0, 50)}${conv.last_message.length > 50 ? '...' : ''}`;
                timeEl.textContent = formatTime(conv.last_message_time);

                if (conv.unread_count > 0) {
                    if (unreadEl) {
                        unreadEl.textContent = conv.unread_count;
                    } else {
                        const parentDiv = timeEl.parentElement;
                        const badge = document.createElement('span');
                        badge.className = 'inline-block mt-1 px-2 py-0.5 bg-purple-600 text-white text-xs rounded-full chat-unread';
                        badge.textContent = conv.unread_count;
                        parentDiv.appendChild(badge);
                    }
                } else if (unreadEl) {
                    unreadEl.remove();
                }

                chatsList.prepend(existingChat);
            } else {
                const newChat = createChatElement(conv);
                chatsList.insertBefore(newChat, chatsList.firstChild);
            }
        });
    }

    function createChatElement(conv) {
        const chat = document.createElement('a');
        chat.href = `/chat/${conv.other_user.id}`;
        chat.className = 'chat-item flex items-center gap-4 p-4 border-b hover:bg-gray-50 transition';
        chat.setAttribute('data-user-id', conv.other_user.id);

        chat.innerHTML = `
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-xl flex-shrink-0 overflow-hidden">
                    ${conv.other_user.avatar ?
            `<img src="/storage/${conv.other_user.avatar}" class="w-full h-full object-cover" alt="Avatar">` :
            conv.other_user.name.charAt(0).toUpperCase()
        }
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-800 truncate chat-name">${conv.other_user.name}</h3>
                    <p class="text-sm text-gray-600 truncate chat-last-message">
                        ${conv.is_sent ? 'Ви: ' : ''}${conv.last_message.substring(0, 50)}${conv.last_message.length > 50 ? '...' : ''}
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-gray-500 chat-time">${formatTime(conv.last_message_time)}</p>
                    ${conv.unread_count > 0 ? `<span class="inline-block mt-1 px-2 py-0.5 bg-purple-600 text-white text-xs rounded-full chat-unread">${conv.unread_count}</span>` : ''}
                </div>
            `;

        return chat;
    }

    setInterval(loadChats, 3000);
</script>
</body>
</html>
