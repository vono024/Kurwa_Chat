<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація - Kurwa Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-500 to-purple-700 min-h-screen flex items-center justify-center p-5">
<div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 md:p-10">
    <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Створити акаунт</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-2">Ім'я</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition">
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-2">Телефон</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+380..."
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition">
            @error('phone')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 font-medium mb-2">Пароль</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition">
            @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Підтвердити пароль</label>
            <input type="password" name="password_confirmation" required
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition">
        </div>

        <button type="submit"
                class="w-full bg-purple-600 text-white py-3 rounded-xl font-semibold hover:bg-purple-700 transition transform hover:-translate-y-0.5 shadow-lg">
            Зареєструватись
        </button>
    </form>

    <p class="text-center text-gray-600 mt-6">
        Вже є акаунт? <a href="{{ route('login') }}" class="text-purple-600 font-semibold hover:underline">Увійти</a>
    </p>
</div>
</body>
</html>
