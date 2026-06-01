<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Naliko Warung')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#E8D5C1] min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-[#7A6247] shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('menu.index') }}" class="text-xl font-bold text-[#F7E6CC]">
                🍽️ Naliko Warung
            </a>
            <div class="flex gap-4 items-center">
                <a href="{{ route('menu.index') }}" class="text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">Menu</a>
                <a href="{{ route('cart') }}" class="text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">🛒 Keranjang</a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" 
                            class="bg-[#5E4A33] text-white px-3 py-1 rounded-lg text-sm hover:bg-[#6e583f]">
                            👑 Admin
                        </a>
                    @elseif(auth()->user()->isKasir())
                        <a href="{{ route('kasir.dashboard') }}" 
                            class="bg-[#5E4A33] text-white px-3 py-1 rounded-lg text-sm hover:bg-[#6e583f]">
                            💰 Kasir
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                        class="bg-[#5E4A33] text-white px-3 py-1 rounded-lg text-sm hover:bg-[#6e583f]">
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
    <footer class="bg-[#7A6247] border-t mt-10 py-4 text-center text-[#D7C2A0] text-sm">
        © 2024 Naliko Warung. All rights reserved.
    </footer>

</body>
</html>