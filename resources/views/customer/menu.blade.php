@extends('layouts.app')

@section('title', 'Menu - Naliko Warung')

@section('content')

{{-- Hero --}}
<div class="bg-[#5C4A35] rounded-2xl p-8 mb-8 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#F7E6CC] rounded-full"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-[#F7E6CC] rounded-full"></div>
    </div>
    <div class="relative text-center">
        <div class="w-16 h-16 bg-[#F7E6CC] bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
            @svg('heroicon-o-building-storefront', 'w-8 h-8 text-[#F7E6CC]')
        </div>
        <h1 class="text-3xl font-bold text-[#F7E6CC] mb-2">Naliko Warung</h1>
        <p class="text-[#dbc7a9]">Pesan makanan & minuman favoritmu dengan mudah!</p>
    </div>
</div>

{{-- Filter Kategori --}}
<div class="flex gap-2 mb-6 overflow-x-auto pb-2">
    <button onclick="filterCategory('all')"
        class="category-btn active flex items-center gap-1 px-4 py-2 rounded-full bg-[#5C4A35] text-[#F7E6CC] text-sm whitespace-nowrap font-semibold shadow-sm">
        @svg('heroicon-o-squares-2x2', 'w-3.5 h-3.5') Semua
    </button>
    @foreach($categories as $category)
    <button onclick="filterCategory('{{ $category->id }}')"
        class="category-btn flex items-center gap-1 px-4 py-2 rounded-full bg-white text-[#9e8065] text-sm whitespace-nowrap border border-[#e8d5c1] hover:border-[#5C4A35] hover:text-[#5C4A35] transition">
        @svg('heroicon-o-tag', 'w-3.5 h-3.5') {{ $category->name }}
    </button>
    @endforeach
</div>

{{-- Produk --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="product-grid">
    @foreach($products as $product)
    <div class="product-card bg-white rounded-2xl shadow-sm border border-[#e8d5c1] overflow-hidden cursor-pointer hover:shadow-md hover:border-[#5C4A35] transition group"
        data-category="{{ $product->category_id }}"
        onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">

        <div class="bg-[#f5e6d3] h-36 flex items-center justify-center overflow-hidden">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
            @else
                @svg('heroicon-o-photo', 'w-10 h-10 text-[#c4a882]')
            @endif
        </div>

        <div class="p-3">
            <h3 class="font-semibold text-[#3E2F1E] text-sm leading-tight">{{ $product->name }}</h3>
            <p class="text-[#5C4A35] font-bold text-sm mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p class="text-[#9e8065] text-xs mt-0.5 flex items-center gap-1">
                @svg('heroicon-o-archive-box', 'w-3 h-3') Stok: {{ $product->stock }}
            </p>

            <button class="add-btn w-full mt-3 flex items-center justify-center gap-1 bg-[#5C4A35] text-[#F7E6CC] py-1.5 rounded-xl text-xs font-semibold hover:bg-[#3E2F1E] transition">
                @svg('heroicon-o-plus', 'w-3.5 h-3.5') Tambah
            </button>
        </div>
    </div>
    @endforeach
</div>

{{-- Cart Float Button --}}
<div id="cart-float" class="fixed bottom-6 right-6 hidden z-50">
    <a href="{{ route('cart') }}"
        class="flex items-center gap-3 bg-[#5C4A35] text-[#F7E6CC] px-5 py-3 rounded-full shadow-lg hover:bg-[#3E2F1E] transition">
        @svg('heroicon-o-shopping-cart', 'w-5 h-5')
        <span class="font-semibold"><span id="cart-count">0</span> item</span>
        @svg('heroicon-o-arrow-right', 'w-4 h-4')
    </a>
</div>

<script>
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
        const card = event.currentTarget;
        const btn = card.querySelector('.add-btn');
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg> Ditambahkan`;
        btn.classList.replace('bg-[#5C4A35]', 'bg-green-600');
        setTimeout(() => {
            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg> Tambah`;
            btn.classList.replace('bg-green-600', 'bg-[#5C4A35]');
        }, 1200);
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
            btn.classList.remove('bg-[#5C4A35]', 'text-[#F7E6CC]');
            btn.classList.add('bg-white', 'text-[#9e8065]', 'border', 'border-[#e8d5c1]');
        });
        event.target.classList.add('bg-[#5C4A35]', 'text-[#F7E6CC]');
        event.target.classList.remove('bg-white', 'text-[#9e8065]', 'border', 'border-[#e8d5c1]');
    }
</script>

@endsection