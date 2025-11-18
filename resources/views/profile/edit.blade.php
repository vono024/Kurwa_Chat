<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати профіль</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<header class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('chats.index') }}" class="text-2xl hover:scale-110 transition">←</a>
            <h1 class="text-xl md:text-2xl font-bold">Редагування профілю</h1>
        </div>
        <div class="hidden md:block">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">Вийти</button>
            </form>
        </div>
        <button onclick="toggleMenu()" class="md:hidden p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
    <div id="mobileMenu" class="hidden md:hidden mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 bg-white/20 rounded-lg">Вийти</button>
        </form>
    </div>
</header>

<div class="container mx-auto px-4 py-6 max-w-3xl">
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex flex-col md:flex-row items-center gap-6 mb-8 pb-6 border-b">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-4xl overflow-hidden">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-gray-700 font-medium mb-2">Аватар</label>
                <input type="file" name="avatar" accept="image/*"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                @error('avatar')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-gray-700 font-medium mb-2">Ім'я</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition">
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-gray-700 font-medium mb-2">Телефон</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition">
                @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-gray-700 font-medium mb-2">Про себе</label>
                <textarea name="bio" rows="4"
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition resize-none">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-purple-600 text-white py-3 rounded-xl font-semibold text-lg hover:bg-purple-700 transition transform hover:-translate-y-0.5 shadow-lg">
                Зберегти зміни
            </button>
        </form>
    </div>
</div>

<script>
    function toggleMenu() {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    }
</script>
</body>
</html>
