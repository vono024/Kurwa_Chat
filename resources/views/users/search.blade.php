<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пошук користувачів</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }
        .header {
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
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .search-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .search-box form {
            display: flex;
            gap: 10px;
        }
        .search-box input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 1em;
        }
        .search-box button {
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
        }
        .user-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5em;
        }
        .user-info {
            flex: 1;
        }
        .user-name {
            font-size: 1.2em;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }
        .user-phone {
            color: #666;
        }
        .chat-btn {
            padding: 10px 25px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
        }
        .chat-btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        /* Адаптивність для мобільних */
        @media (max-width: 768px) {
            .header {
                padding: 12px 15px;
            }
            .header h1 {
                font-size: 1.3em;
            }
            .container {
                margin: 20px auto;
                padding: 0 15px;
            }
            .search-box {
                padding: 15px;
            }
            .search-box form {
                flex-direction: column;
            }
            .search-box button {
                width: 100%;
                padding: 12px;
            }
            .user-card {
                padding: 15px;
                flex-wrap: wrap;
            }
            .avatar {
                width: 50px;
                height: 50px;
                font-size: 1.3em;
            }
            .user-name {
                font-size: 1.1em;
            }
            .chat-btn {
                width: 100%;
                text-align: center;
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 10px 12px;
            }
            .back-btn {
                font-size: 1.3em;
            }
            .header h1 {
                font-size: 1.1em;
            }
            .search-box input {
                padding: 10px 15px;
                font-size: 0.95em;
            }
        }
    </style>
</head>
<body>
<div class="header">
    <a href="{{ route('chats.index') }}" class="back-btn">←</a>
    <h1>Пошук користувачів</h1>
</div>

<div class="container">
    <div class="search-box">
        <form method="GET" action="{{ route('users.search') }}">
            <input type="text" name="query" placeholder="Введіть ім'я або номер телефону..." value="{{ request('query') }}" required>
            <button type="submit">🔍 Шукати</button>
        </form>
    </div>

    @if(isset($users))
        @if($users->count() > 0)
            @foreach($users as $user)
                <div class="user-card">
                    <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-phone">{{ $user->phone }}</div>
                    </div>
                    <a href="{{ route('chat.show', $user) }}" class="chat-btn">Написати</a>
                </div>
            @endforeach
        @else
            <div class="no-results">
                <h3>Нічого не знайдено</h3>
                <p>Спробуйте інший запит</p>
            </div>
        @endif
    @endif
</div>
</body>
</html>
