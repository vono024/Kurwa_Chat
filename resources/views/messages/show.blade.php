<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Чат з {{ $user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        .back-btn {
            color: white;
            text-decoration: none;
            font-size: 1.5em;
            transition: transform 0.2s;
            cursor: pointer;
        }
        .back-btn:hover {
            transform: scale(1.2);
        }
        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2em;
            flex-shrink: 0;
        }
        .user-info {
            flex: 1;
            min-width: 0;
        }
        .user-info h2 {
            font-size: 1.2em;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-phone {
            font-size: 0.9em;
            opacity: 0.9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #e5ddd5;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d1d1d1' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .message {
            max-width: 70%;
            margin-bottom: 15px;
            clear: both;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .message.sent {
            float: right;
        }
        .message.received {
            float: left;
        }
        .message-bubble {
            padding: 10px 15px;
            border-radius: 12px;
            word-wrap: break-word;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .message.sent .message-bubble {
            background: #dcf8c6;
            border-bottom-right-radius: 2px;
        }
        .message.received .message-bubble {
            background: white;
            border-bottom-left-radius: 2px;
        }
        .message-time {
            font-size: 0.75em;
            color: #666;
            margin-top: 4px;
            text-align: right;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        .input-container {
            background: white;
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-shrink: 0;
        }
        .input-container input {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 1em;
            outline: none;
        }
        .input-container input:focus {
            border-color: #667eea;
        }
        .send-btn {
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s;
            white-space: nowrap;
        }
        .send-btn:hover {
            background: #5568d3;
            transform: scale(1.05);
        }
        .send-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        .no-messages {
            text-align: center;
            color: #666;
            padding: 40px 20px;
        }
        .typing-indicator {
            display: none;
            padding: 10px;
            color: #666;
            font-size: 0.9em;
            font-style: italic;
        }

        /* Адаптивність для мобільних */
        @media (max-width: 768px) {
            .chat-header {
                padding: 12px 15px;
            }
            .avatar {
                width: 40px;
                height: 40px;
                font-size: 1em;
            }
            .user-info h2 {
                font-size: 1.1em;
            }
            .user-phone {
                font-size: 0.85em;
            }
            .messages-container {
                padding: 15px 10px;
            }
            .message {
                max-width: 85%;
            }
            .message-bubble {
                padding: 8px 12px;
            }
            .input-container {
                padding: 10px 15px;
                gap: 8px;
            }
            .input-container input {
                padding: 10px 15px;
                font-size: 0.95em;
            }
            .send-btn {
                padding: 10px 20px;
                font-size: 0.95em;
            }
        }

        /* Адаптивність для маленьких екранів */
        @media (max-width: 480px) {
            .chat-header {
                padding: 10px 12px;
            }
            .back-btn {
                font-size: 1.3em;
            }
            .avatar {
                width: 35px;
                height: 35px;
                font-size: 0.9em;
            }
            .user-info h2 {
                font-size: 1em;
            }
            .user-phone {
                font-size: 0.8em;
            }
            .messages-container {
                padding: 10px 8px;
            }
            .message {
                max-width: 90%;
            }
            .input-container {
                padding: 8px 10px;
            }
            .send-btn {
                padding: 10px 15px;
                font-size: 0.9em;
            }
        }

        /* Горизонтальна орієнтація на мобільних */
        @media (max-width: 768px) and (orientation: landscape) {
            .chat-header {
                padding: 8px 12px;
            }
            .messages-container {
                padding: 10px;
            }
            .input-container {
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
<div class="chat-header">
    <a href="{{ route('chats.index') }}" class="back-btn">←</a>
    <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
    <div class="user-info">
        <h2>{{ $user->name }}</h2>
        <div class="user-phone">{{ $user->phone }}</div>
    </div>
</div>

<div class="messages-container" id="messagesContainer">
    <div id="messagesList">
        @if($messages->count() > 0)
            @foreach($messages as $message)
                <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}" data-id="{{ $message->id }}">
                    <div class="message-bubble">
                        {{ $message->content }}
                        <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-messages" id="noMessages">
                <h3>Це початок вашої розмови з {{ $user->name }}</h3>
                <p>Відправте перше повідомлення!</p>
            </div>
        @endif
    </div>
    <div class="clearfix"></div>
</div>

<form id="messageForm" class="input-container">
    @csrf
    <input type="text" id="messageInput" name="content" placeholder="Напишіть повідомлення..." required autocomplete="off">
    <button type="submit" class="send-btn" id="sendBtn">Відправити</button>
</form>

<script>
    const userId = {{ $user->id }};
    const authUserId = {{ auth()->id() }};
    const messagesContainer = document.getElementById('messagesContainer');
    const messagesList = document.getElementById('messagesList');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const noMessages = document.getElementById('noMessages');

    let lastMessageId = {{ $messages->last()->id ?? 0 }};
    let isAtBottom = true;

    // Перевірка чи користувач внизу
    messagesContainer.addEventListener('scroll', () => {
        const threshold = 100;
        isAtBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight < threshold;
    });

    // Прокрутити вниз
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Початкова прокрутка
    scrollToBottom();

    // Форматування часу
    function formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString('uk-UA', { hour: '2-digit', minute: '2-digit' });
    }

    // Додати повідомлення в DOM
    function addMessage(message) {
        if (noMessages) {
            noMessages.remove();
        }

        const isSent = message.sender_id === authUserId;
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
        messageDiv.setAttribute('data-id', message.id);
        messageDiv.innerHTML = `
                <div class="message-bubble">
                    ${message.content}
                    <div class="message-time">${formatTime(message.created_at)}</div>
                </div>
            `;

        messagesList.appendChild(messageDiv);

        const clearDiv = document.createElement('div');
        clearDiv.className = 'clearfix';
        messagesList.appendChild(clearDiv);

        if (isAtBottom) {
            scrollToBottom();
        }
    }

    // Відправити повідомлення (AJAX)
    messageForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const content = messageInput.value.trim();
        if (!content) return;

        sendBtn.disabled = true;
        sendBtn.textContent = 'Відправка...';

        try {
            const response = await fetch(`/chat/${userId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ content })
            });

            if (response.ok) {
                messageInput.value = '';
                messageInput.focus();

                // Отримати нові повідомлення
                await loadNewMessages();
            }
        } catch (error) {
            console.error('Помилка відправки:', error);
            alert('Помилка відправки повідомлення');
        } finally {
            sendBtn.disabled = false;
            sendBtn.textContent = 'Відправити';
        }
    });

    // Завантажити нові повідомлення
    async function loadNewMessages() {
        try {
            const response = await fetch(`/chat/${userId}/messages`);
            const data = await response.json();

            data.messages.forEach(message => {
                if (message.id > lastMessageId) {
                    addMessage(message);
                    lastMessageId = message.id;
                }
            });
        } catch (error) {
            console.error('Помилка завантаження повідомлень:', error);
        }
    }

    // Перевіряти нові повідомлення кожні 2 секунди
    setInterval(loadNewMessages, 2000);

    // Фокус на інпуті при завантаженні
    messageInput.focus();
</script>
</body>
</html>
