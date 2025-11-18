<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пошук користувачів</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
<header class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('chats.index') }}" class="text-2xl hover:scale-110 transition">←</a>
            <h1 class="text-xl md:text-2xl font-bold">🔍 Пошук користувачів</h1>
        </div>
        <div class="hidden md:flex gap-3 items-center">
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">⚙️ Профіль</a>
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
    <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 bg-white/20 rounded-lg">⚙️ Профіль</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 bg-white/20 rounded-lg">Вийти</button>
        </form>
    </div>
</header>

<div class="container mx-auto px-4 py-6 max-w-4xl flex-1">
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <input type="text" id="searchInput" placeholder="Введіть ім'я або номер телефону..." autofocus
               class="w-full px-6 py-4 border-2 border-gray-300 rounded-full text-lg focus:outline-none focus:border-purple-500 transition">
    </div>

    <div id="searchResults"></div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        const query = searchInput.value.trim();

        if (!query) {
            searchResults.innerHTML = '';
            return;
        }

        searchTimeout = setTimeout(() => {
            searchUsers(query);
        }, 500);
    });

    async function searchUsers(query) {
        searchResults.innerHTML = '<div class="text-center py-8"><div class="inline-block w-8 h-8 border-4 border-purple-600 border-t-transparent rounded-full animate-spin"></div></div>';

        try {
            const response = await fetch(`/search/users?query=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.users.length === 0) {
                searchResults.innerHTML = `
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Нічого не знайдено</h3>
                            <p class="text-gray-500">Спробуйте інший запит</p>
                        </div>
                    `;
                return;
            }

            searchResults.innerHTML = data.users.map(user => `
                    <div class="bg-white rounded-2xl shadow-lg p-5 mb-4 flex items-center gap-4 hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-xl flex-shrink-0 overflow-hidden">
                            ${user.avatar ? `<img src="/storage/${user.avatar}" class="w-full h-full object-cover" alt="Avatar">` : user.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-800 truncate">${user.name}</h3>
                            <p class="text-sm text-gray-600 truncate">${user.phone}</p>
                        </div>
                        <a href="/chat/${user.id}" class="px-6 py-2 bg-purple-600 text-white rounded-full font-semibold hover:bg-purple-700 transition whitespace-nowrap">
                            Написати
                        </a>
                    </div>
                `).join('');
        } catch (error) {
            console.error('Помилка:', error);
            searchResults.innerHTML = `
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <h3 class="text-xl font-semibold text-red-600 mb-2">Помилка</h3>
                        <p class="text-gray-500">Спробуйте ще раз</p>
                    </div>
                `;
        }
    }

    function toggleMenu() {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    }
</script>
</body>
</html>
