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
<body class="bg-[#E8D5C1] min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-[#7A6247] shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3">

            {{-- Baris 1: Logo + Menu Links --}}
            <div class="flex justify-between items-center mb-2">
                <a href="{{ route('menu.index') }}" class="text-xl font-bold text-[#F7E6CC]">
                    🍽️ Naliko Warung
                </a>
                <div class="flex gap-4 items-center">
                    {{-- Menu & Alamat & Kontak tampil untuk semua --}}
                    <a href="{{ route('menu.index') }}" class="text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">Menu</a>
                    <a href="#alamat" class="text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">📍 Alamat</a>
                    <a href="#kontak" class="text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">📞 Kontak</a>

                    {{-- Keranjang hanya untuk pelanggan --}}
                    @guest
                        <a href="{{ route('cart') }}" class="text-[#F7E6CC] hover:text-[#dbc7a9] text-sm">🛒 Keranjang</a>
                    @endguest

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
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="button" onclick="confirmLogout()"
                                class="text-[#F7E6CC] hover:text-red-300 text-sm">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-[#5E4A33] text-white px-3 py-1 rounded-lg text-sm hover:bg-[#6e583f]">
                            Login
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Baris 2: Search Bar (hanya untuk pelanggan) --}}
            @guest
            <div class="relative">
                <input type="text" id="navbar-search"
                    placeholder="🔍 Cari menu makanan atau minuman..."
                    onkeyup="navbarSearch()"
                    class="w-full border border-[#9e8065] rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#5E4A33] bg-[#f5e6d3] text-[#5E4A33] placeholder-[#9e8065]">

                <div id="search-results"
                    class="absolute top-full left-0 right-0 bg-white rounded-xl shadow-lg mt-1 hidden z-50 max-h-72 overflow-y-auto">
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
    <footer class="bg-[#7A6247] border-t mt-10">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                {{-- Brand --}}
                <div>
                    <h3 class="text-lg font-bold text-[#F7E6CC] mb-2">🍽️ Naliko Warung</h3>
                    <p class="text-[#D7C2A0] text-sm">Menyajikan makanan & minuman lezat dengan harga terjangkau.</p>
                </div>

                {{-- Kontak --}}
                <div id="kontak">
                    <h4 class="font-semibold text-[#F7E6CC] mb-3">📞 Kontak</h4>
                    <ul class="space-y-2 text-sm text-[#D7C2A0]">
                        <li>
                            <a href="tel:+6281234567890" class="flex items-center gap-2 hover:text-white">
                                📞 +62 812-3456-7890
                            </a>
                        </li>
                        <li>
                            <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center gap-2 hover:text-white">
                                💬 WhatsApp
                            </a>
                        </li>
                        <li>
                            <a href="mailto:naliko@gmail.com" class="flex items-center gap-2 hover:text-white">
                                📧 naliko@gmail.com
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Alamat --}}
                <div id="alamat">
                    <h4 class="font-semibold text-[#F7E6CC] mb-3">📍 Lokasi & Jam Buka</h4>
                    <ul class="space-y-2 text-sm text-[#D7C2A0]">
                        <li>📍 Jl. Contoh No. 123, Yogyakarta</li>
                        <li>🕐 Senin - Minggu: 08.00 - 21.00 WIB</li>
                        <li class="mt-2">
                            <a href="https://maps.google.com" target="_blank"
                                class="bg-[#5E4A33] text-white px-3 py-1 rounded-lg text-xs hover:bg-[#6e583f]">
                                Lihat di Google Maps
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-[#9e8065] pt-4 text-center text-[#D7C2A0] text-sm">
                © {{ date('Y') }} Naliko Warung. All rights reserved.
            </div>
        </div>
    </footer>

    {{-- Modal Konfirmasi Logout --}}
    <div id="logout-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 text-center shadow-xl">
            <div class="text-5xl mb-4">👋</div>
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

            if (query.length < 1) {
                resultsDiv.classList.add('hidden');
                return;
            }

            const filtered = allProducts.filter(p =>
                p.name.toLowerCase().includes(query) ||
                (p.description && p.description.toLowerCase().includes(query)) ||
                p.category.name.toLowerCase().includes(query)
            );

            if (filtered.length === 0) {
                resultsDiv.innerHTML = `<div class="px-4 py-6 text-center text-gray-400 text-sm">😔 Menu "${query}" tidak ditemukan</div>`;
                resultsDiv.classList.remove('hidden');
                return;
            }

            resultsDiv.innerHTML = filtered.map(p => `
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50 cursor-pointer border-b last:border-0"
                    onclick="addToCartFromSearch(${p.id}, '${p.name}', ${p.price})">
                    <div class="bg-[#f5e6d3] rounded-lg w-10 h-10 flex items-center justify-center text-lg flex-shrink-0">🍽️</div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">${p.name}</p>
                        <p class="text-gray-400 text-xs">${p.category.name}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[#7A6247] font-bold text-sm">Rp ${formatRupiah(p.price)}</p>
                        <button class="text-xs bg-[#7A6247] text-white px-2 py-0.5 rounded-lg mt-0.5">+ Tambah</button>
                    </div>
                </div>
            `).join('');

            resultsDiv.classList.remove('hidden');
        }

        function addToCartFromSearch(id, name, price) {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const existing = cart.find(i => i.id === id);
            if (existing) {
                existing.qty++;
            } else {
                cart.push({ id, name, price, qty: 1 });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            document.getElementById('navbar-search').value = '';
            document.getElementById('search-results').classList.add('hidden');
            showToast(`✅ ${name} ditambahkan ke keranjang!`);
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-[#7A6247] text-white px-6 py-3 rounded-xl shadow-lg z-50 text-sm';
            toast.textContent = message;
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

        function confirmLogout() {
            document.getElementById('logout-modal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logout-modal').classList.add('hidden');
        }
    </script>

</body>
</html>