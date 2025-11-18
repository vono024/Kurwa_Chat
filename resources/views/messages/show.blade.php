<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .back-btn {
            color: white;
            text-decoration: none;
            font-size: 1.5em;
            transition: transform 0.2s;
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
        }
        .user-info h2 {
            font-size: 1.2em;
            margin-bottom: 2px;
        }
        .user-phone {
            font-size: 0.9em;
            opacity: 0.9;
        }
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #e5ddd5;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d1d1d1' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .message {
            max-width: 60%;
            margin-bottom: 15px;
            clear: both;
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
        }
        .send-btn:hover {
            background: #5568d3;
            transform: scale(1.05);
        }
        .no-messages {
            text-align: center;
            color: #666;
            padding: 40px 20px;
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
        @if($messages->count() > 0)
            @foreach($messages as $message)
                <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
                    <div class="message-bubble">
                        {{ $message->content }}
                        <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-messages">
                <h3>Це початок вашої розмови з {{ $user->name }}</h3>
                <p>Відправте перше повідомлення!</p>
            </div>
        @endif
        <div class="clearfix"></div>
    </div>

    <form method="POST" action="{{ route('message.send', $user) }}" class="input-container">
        @csrf
        <input type="text" name="content" placeholder="Напишіть повідомлення..." required autocomplete="off">
        <button type="submit" class="send-btn">Відправити</button>
    </form>

    <script>
        // Прокрутити до останнього повідомлення
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;

        // Авто-оновлення кожні 3 секунди
        setInterval(() => {
            location.reload();
        }, 3000);
    </script>
</body>
</html>
