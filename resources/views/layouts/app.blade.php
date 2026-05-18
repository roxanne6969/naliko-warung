<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Naliko Warung')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-orange-500">
                        🍽️ Naliko Warung
                    </a>
                @elseif(auth()->user()->isKasir())
                    <a href="{{ route('kasir.dashboard') }}" class="text-xl font-bold text-orange-500">
                        🍽️ Naliko Warung
                    </a>
                @else
                    <a href="{{ route('menu.index') }}" class="text-xl font-bold text-orange-500">
                        🍽️ Naliko Warung
                    </a>
                @endif
            @else
                <a href="{{ route('menu.index') }}" class="text-xl font-bold text-orange-500">
                    🍽️ Naliko Warung
                </a>
            @endauth
            <div class="flex gap-4 items-center">
                <a href="{{ route('menu.index') }}" class="text-gray-600 hover:text-orange-500 text-sm">Menu</a>
                <a href="{{ route('cart') }}" class="text-gray-600 hover:text-orange-500 text-sm">🛒 Keranjang</a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" 
                            class="bg-purple-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-purple-600">
                            👑 Admin
                        </a>
                    @elseif(auth()->user()->isKasir())
                        <a href="{{ route('kasir.dashboard') }}" 
                            class="bg-orange-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-orange-600">
                            💰 Kasir
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-gray-400 hover:text-red-500 text-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                        class="bg-orange-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-orange-600">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="max-w-6xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t mt-10 py-4 text-center text-gray-500 text-sm">
        © 2024 Naliko Warung. All rights reserved.
    </footer>

</body>
</html>