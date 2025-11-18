<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurwa Chat - Повідомлення</title>
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
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 {
            font-size: 1.5em;
        }
        .header-right {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .header-right a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(255,255,255,0.2);
            transition: all 0.3s;
        }
        .header-right a:hover {
            background: rgba(255,255,255,0.3);
        }
        .header-right button {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .header-right button:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .chats-list {
            width: 100%;
            border-right: 1px solid #e0e0e0;
            overflow-y: auto;
        }
        .chat-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: inherit;
        }
        .chat-item:hover {
            background: #f8f9fa;
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2em;
            flex-shrink: 0;
        }
        .chat-info {
            flex: 1;
            min-width: 0;
        }
        .chat-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }
        .chat-last-message {
            color: #666;
            font-size: 0.9em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .chat-time {
            color: #999;
            font-size: 0.85em;
        }
        .no-chats {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .no-chats h3 {
            margin-bottom: 10px;
            color: #666;
        }
        .search-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
        }
        .search-btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .unread-badge {
            background: #667eea;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>💬 Kurwa Chat</h1>
        <div class="header-right">
            <span>{{ auth()->user()->name }}</span>
            <a href="{{ route('users.search') }}">🔍 Пошук</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit">Вийти</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="chats-list">
            @if(isset($conversations) && $conversations->count() > 0)
                @foreach($conversations as $userId => $messages)
                    @php
                        $lastMessage = $messages->last();
                        $otherUser = $lastMessage->sender_id === auth()->id() 
                            ? $lastMessage->receiver 
                            : $lastMessage->sender;
                        $unreadCount = $messages->where('sender_id', $otherUser->id)
                                                 ->where('is_read', false)
                                                 ->count();
                    @endphp
                    <a href="{{ route('chat.show', $otherUser) }}" class="chat-item">
                        <div class="avatar">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                        </div>
                        <div class="chat-info">
                            <div class="chat-name">{{ $otherUser->name }}</div>
                            <div class="chat-last-message">
                                {{ $lastMessage->sender_id === auth()->id() ? 'Ви: ' : '' }}
                                {{ Str::limit($lastMessage->content, 50) }}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div class="chat-time">{{ $lastMessage->created_at->format('H:i') }}</div>
                            @if($unreadCount > 0)
                                <div class="unread-badge">{{ $unreadCount }}</div>
                            @endif
                        </div>
                    </a>
                @endforeach
            @else
                <div class="no-chats">
                    <h3>Немає активних чатів</h3>
                    <p>Знайдіть користувачів по номеру телефону і почніть спілкуватися!</p>
                    <a href="{{ route('users.search') }}" class="search-btn">Знайти користувачів</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
