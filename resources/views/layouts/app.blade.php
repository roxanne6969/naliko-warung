<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Naliko Warung')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-[#F0E0CC] min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-[#5C4A35] shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3">

            {{-- Baris 1: Logo + Menu Links --}}
            <div class="flex justify-between items-center mb-2">
                <a href="{{ route('menu.index') }}" class="flex items-center gap-2 text-xl font-bold text-[#F7E6CC]">
                    @svg('heroicon-o-building-storefront', 'w-6 h-6')
                    Naliko Warung
                </a>
                <div class="flex gap-4 items-center">

                    @guest
                        <a href="{{ route('menu.index') }}" class="text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">Menu</a>
                        <a href="#alamat" class="flex items-center gap-1 text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">
                            @svg('heroicon-o-map-pin', 'w-4 h-4') Alamat
                        </a>
                        <a href="#kontak" class="flex items-center gap-1 text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">
                            @svg('heroicon-o-phone', 'w-4 h-4') Kontak
                        </a>
                        <a href="{{ route('cart') }}" class="flex items-center gap-1 text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">
                            @svg('heroicon-o-shopping-cart', 'w-4 h-4') Keranjang
                        </a>
                    @endguest

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('menu.index') }}" class="text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">Menu</a>
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex items-center gap-1 bg-[#3E2F1E] text-[#F7E6CC] px-3 py-1.5 rounded-lg text-sm hover:bg-[#4a3828]">
                                @svg('heroicon-o-squares-2x2', 'w-4 h-4') Admin
                            </a>
                        @elseif(auth()->user()->isKasir())
                            <a href="{{ route('kasir.dashboard') }}"
                                class="flex items-center gap-1 bg-[#3E2F1E] text-[#F7E6CC] px-3 py-1.5 rounded-lg text-sm hover:bg-[#4a3828]">
                                @svg('heroicon-o-squares-2x2', 'w-4 h-4') Kasir
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="button" onclick="confirmLogout()"
                                class="flex items-center gap-1 text-[#F7E6CC] hover:text-red-300 text-sm">
                                @svg('heroicon-o-arrow-right-on-rectangle', 'w-4 h-4') Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex items-center gap-1 bg-[#3E2F1E] text-[#F7E6CC] px-3 py-1.5 rounded-lg text-sm hover:bg-[#4a3828]">
                            @svg('heroicon-o-arrow-left-on-rectangle', 'w-4 h-4') Login
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Search Bar --}}
            @guest
            <div class="relative">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    @svg('heroicon-o-magnifying-glass', 'w-4 h-4 text-[#9e8065]')
                </div>
                <input type="text" id="navbar-search"
                    placeholder="Cari menu makanan atau minuman..."
                    onkeyup="navbarSearch()"
                    class="w-full border border-[#9e8065] rounded-xl pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3E2F1E] bg-[#f5e6d3] text-[#5C4A35] placeholder-[#9e8065]">
                <div id="search-results"
                    class="absolute top-full left-0 right-0 bg-white rounded-xl shadow-lg mt-1 hidden z-50 max-h-72 overflow-y-auto border border-[#e0cdb8]">
                </div>
            </div>
            @endguest
        </div>
    </nav>

    {{-- Content --}}
    <main class="max-w-6xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#5C4A35] border-t border-[#4a3828] mt-10">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <div>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-[#F7E6CC] mb-2">
                        @svg('heroicon-o-building-storefront', 'w-5 h-5')
                        Naliko Warung
                    </h3>
                    <p class="text-[#D7C2A0] text-sm">Menyajikan makanan & minuman lezat dengan harga terjangkau.</p>
                </div>

                <div id="kontak">
                    <h4 class="flex items-center gap-2 font-semibold text-[#F7E6CC] mb-3">
                        @svg('heroicon-o-phone', 'w-4 h-4') Kontak
                    </h4>
                    <ul class="space-y-2 text-sm text-[#D7C2A0]">
                        <li>
                            <a href="tel:+6281234567890" class="flex items-center gap-2 hover:text-white">
                                @svg('heroicon-o-phone', 'w-4 h-4') +62 812-3456-7890
                            </a>
                        </li>
                        <li>
                            <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center gap-2 hover:text-white">
                                @svg('heroicon-o-chat-bubble-left-ellipsis', 'w-4 h-4') WhatsApp
                            </a>
                        </li>
                        <li>
                            <a href="mailto:naliko@gmail.com" class="flex items-center gap-2 hover:text-white">
                                @svg('heroicon-o-envelope', 'w-4 h-4') naliko@gmail.com
                            </a>
                        </li>
                    </ul>
                </div>

                <div id="alamat">
                    <h4 class="flex items-center gap-2 font-semibold text-[#F7E6CC] mb-3">
                        @svg('heroicon-o-map-pin', 'w-4 h-4') Lokasi & Jam Buka
                    </h4>
                    <ul class="space-y-2 text-sm text-[#D7C2A0]">
                        <li class="flex items-center gap-2">
                            @svg('heroicon-o-map-pin', 'w-4 h-4') Jl. Contoh No. 123, Yogyakarta
                        </li>
                        <li class="flex items-center gap-2">
                            @svg('heroicon-o-clock', 'w-4 h-4') Senin - Minggu: 08.00 - 21.00 WIB
                        </li>
                        <li class="mt-2">
                            <a href="https://maps.google.com" target="_blank"
                                class="inline-flex items-center gap-1 bg-[#3E2F1E] text-[#F7E6CC] px-3 py-1 rounded-lg text-xs hover:bg-[#4a3828]">
                                @svg('heroicon-o-arrow-top-right-on-square', 'w-3 h-3') Lihat di Google Maps
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-[#9e8065] pt-4 text-center text-[#D7C2A0] text-sm">
                &copy; {{ date('Y') }} Naliko Warung. All rights reserved.
            </div>
        </div>
    </footer>

    {{-- Modal Konfirmasi Logout --}}
    <div id="logout-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 text-center shadow-xl border border-[#e0cdb8]">
            <div class="w-14 h-14 bg-[#f5e6d3] rounded-full flex items-center justify-center mx-auto mb-4">
                @svg('heroicon-o-arrow-right-on-rectangle', 'w-7 h-7 text-[#5C4A35]')
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Yakin ingin logout?</h3>
            <p class="text-gray-400 text-sm mb-6">Kamu akan keluar dari sesi ini.</p>
            <div class="flex gap-3">
                <button onclick="closeLogoutModal()"
                    class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-xl hover:bg-gray-50">
                    Batal
                </button>
                <button onclick="document.getElementById('logout-form').submit()"
                    class="flex-1 bg-red-500 text-white py-2 rounded-xl hover:bg-red-600">
                    Ya, Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        const allProducts = @json(\App\Models\Product::where('is_available', true)->with('category')->get());

        function navbarSearch() {
            const query = document.getElementById('navbar-search').value.toLowerCase().trim();
            const resultsDiv = document.getElementById('search-results');
            if (query.length < 1) { resultsDiv.classList.add('hidden'); return; }
            const filtered = allProducts.filter(p =>
                p.name.toLowerCase().includes(query) ||
                (p.description && p.description.toLowerCase().includes(query)) ||
                p.category.name.toLowerCase().includes(query)
            );
            if (filtered.length === 0) {
                resultsDiv.innerHTML = `<div class="px-4 py-6 text-center text-gray-400 text-sm">Menu "${query}" tidak ditemukan</div>`;
                resultsDiv.classList.remove('hidden');
                return;
            }
            resultsDiv.innerHTML = filtered.map(p => `
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-[#fdf5ec] cursor-pointer border-b last:border-0"
                    onclick="addToCartFromSearch(${p.id}, '${p.name}', ${p.price})">
                    <div class="bg-[#f5e6d3] rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#7A6247]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" /></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">${p.name}</p>
                        <p class="text-gray-400 text-xs">${p.category.name}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[#5C4A35] font-bold text-sm">Rp ${formatRupiah(p.price)}</p>
                        <span class="text-xs bg-[#5C4A35] text-white px-2 py-0.5 rounded-lg mt-0.5 inline-block">+ Tambah</span>
                    </div>
                </div>
            `).join('');
            resultsDiv.classList.remove('hidden');
        }

        function addToCartFromSearch(id, name, price) {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const existing = cart.find(i => i.id === id);
            if (existing) { existing.qty++; } else { cart.push({ id, name, price, qty: 1 }); }
            localStorage.setItem('cart', JSON.stringify(cart));
            document.getElementById('navbar-search').value = '';
            document.getElementById('search-results').classList.add('hidden');
            showToast(name + ' ditambahkan ke keranjang');
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-[#5C4A35] text-white px-6 py-3 rounded-xl shadow-lg z-50 text-sm flex items-center gap-2';
            toast.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>${message}`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2500);
        }

        function formatRupiah(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        document.addEventListener('click', function(e) {
            const search = document.getElementById('navbar-search');
            const results = document.getElementById('search-results');
            if (search && results && !search.contains(e.target) && !results.contains(e.target)) {
                results.classList.add('hidden');
            }
        });

        function confirmLogout() { document.getElementById('logout-modal').classList.remove('hidden'); }
        function closeLogoutModal() { document.getElementById('logout-modal').classList.add('hidden'); }
    </script>
</body>
</html>