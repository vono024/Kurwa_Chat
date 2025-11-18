<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurwa Chat - Вітаємо</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 60px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2.5em;
        }
        p {
            color: #666;
            margin-bottom: 40px;
            font-size: 1.2em;
        }
        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .btn {
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.1em;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🎉 Вітаємо в Kurwa Chat!</h1>
    <p>Спілкуйтесь з друзями, діліться моментами та залишайтесь на зв'язку</p>
    <div class="buttons">
        <a href="{{ route('login') }}" class="btn btn-primary">Увійти</a>
        <a href="{{ route('register') }}" class="btn btn-secondary">Зареєструватись</a>
    </div>
</div>
</body>
</html>
