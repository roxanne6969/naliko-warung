@extends('layouts.app')

@section('title', 'Menu - Naliko Warung')

@section('content')

{{-- Hero --}}
<div class="bg-orange-500 rounded-2xl p-8 mb-8 text-white text-center">
    <h1 class="text-3xl font-bold mb-2">🍽️ Naliko Warung</h1>
    <p class="text-orange-100">Pesan makanan & minuman favoritmu dengan mudah!</p>
</div>

{{-- Filter Kategori --}}
<div class="flex gap-2 mb-6 overflow-x-auto pb-2">
    <button onclick="filterCategory('all')" 
        class="category-btn active px-4 py-2 rounded-full bg-orange-500 text-white text-sm whitespace-nowrap">
        Semua
    </button>
    @foreach($categories as $category)
    <button onclick="filterCategory('{{ $category->id }}')"
        class="category-btn px-4 py-2 rounded-full bg-white text-gray-600 text-sm whitespace-nowrap shadow">
        {{ $category->name }}
    </button>
    @endforeach
</div>

{{-- Produk --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="product-grid">
    @foreach($products as $product)
    <div class="product-card bg-white rounded-xl shadow p-4 cursor-pointer hover:shadow-md transition"
        data-category="{{ $product->category_id }}"
        onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
        
        <div class="bg-orange-100 rounded-lg h-32 flex items-center justify-center mb-3">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-cover rounded-lg">
            @else
                <span class="text-4xl">🍽️</span>
            @endif
        </div>

        <h3 class="font-semibold text-gray-800 text-sm">{{ $product->name }}</h3>
        <p class="text-orange-500 font-bold text-sm mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        <p class="text-gray-400 text-xs mt-1">Stok: {{ $product->stock }}</p>

        <button class="w-full mt-3 bg-orange-500 text-white py-1.5 rounded-lg text-sm hover:bg-orange-600 transition">
            + Tambah
        </button>
    </div>
    @endforeach
</div>

{{-- Cart Float Button --}}
<div id="cart-float" class="fixed bottom-6 right-6 hidden">
    <a href="{{ route('cart') }}" 
        class="bg-orange-500 text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-2 hover:bg-orange-600 transition">
        🛒 <span id="cart-count">0</span> item
    </a>
</div>

<script>
    // Cart dari localStorage
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    updateCartFloat();

    function addToCart(id, name, price) {
        const existing = cart.find(i => i.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartFloat();

        // Feedback visual
        const btn = event.currentTarget.querySelector('button');
        btn.textContent = '✓ Ditambahkan';
        btn.classList.replace('bg-orange-500', 'bg-green-500');
        setTimeout(() => {
            btn.textContent = '+ Tambah';
            btn.classList.replace('bg-green-500', 'bg-orange-500');
        }, 1000);
    }

    function updateCartFloat() {
        const total = cart.reduce((sum, i) => sum + i.qty, 0);
        document.getElementById('cart-count').textContent = total;
        document.getElementById('cart-float').classList.toggle('hidden', total === 0);
    }

    function filterCategory(id) {
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = (id === 'all' || card.dataset.category == id) ? 'block' : 'none';
        });
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('bg-orange-500', 'text-white');
            btn.classList.add('bg-white', 'text-gray-600');
        });
        event.target.classList.add('bg-orange-500', 'text-white');
        event.target.classList.remove('bg-white', 'text-gray-600');
    }
</script>

@endsection