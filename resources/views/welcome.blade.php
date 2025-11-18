<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurwa Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-500 to-purple-700 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-3xl shadow-2xl p-12 md:p-16 text-center max-w-2xl w-full">
    <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-6">Вітаємо в Kurwa Chat!</h1>
    <p class="text-lg md:text-xl text-gray-600 mb-10">Спілкуйтесь з друзями, діліться моментами та залишайтесь на зв'язку</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('login') }}"
           class="px-10 py-4 bg-purple-600 text-white rounded-full text-lg font-semibold hover:bg-purple-700 transition transform hover:-translate-y-1 shadow-lg">
            Увійти
        </a>
        <a href="{{ route('register') }}"
           class="px-10 py-4 bg-white text-purple-600 border-2 border-purple-600 rounded-full text-lg font-semibold hover:bg-purple-600 hover:text-white transition transform hover:-translate-y-1">
            Зареєструватись
        </a>
    </div>
</div>
</body>
</html>
