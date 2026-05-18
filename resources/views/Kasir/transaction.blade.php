@extends('layouts.app')

@section('title', 'Transaksi - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">💰 Transaksi Manual</h2>
    <a href="{{ route('kasir.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Pilih Produk --}}
    <div>
        <input type="text" id="search" placeholder="🔍 Cari produk..." 
            class="w-full border rounded-xl px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-300"
            oninput="filterProducts()">

        <div class="grid grid-cols-2 gap-3 max-h-96 overflow-y-auto" id="product-list">
            @foreach($products as $product)
            <div class="product-item bg-white rounded-xl shadow p-3 cursor-pointer hover:shadow-md transition"
                data-name="{{ strtolower($product->name) }}"
                onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                <p class="font-semibold text-sm text-gray-800">{{ $product->name }}</p>
                <p class="text-orange-500 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-gray-400 text-xs">Stok: {{ $product->stock }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Keranjang Kasir --}}
    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-bold text-gray-800 mb-4">🧾 Nota</h3>

        <div id="cart-list" class="space-y-2 mb-4 max-h-64 overflow-y-auto">
            <p id="cart-empty-msg" class="text-gray-400 text-sm text-center py-4">Belum ada item</p>
        </div>

        <div class="border-t pt-3 space-y-2">
            <div class="flex justify-between font-bold text-orange-500 text-lg">
                <span>Total</span>
                <span id="total-display">Rp 0</span>
            </div>
            <div>
                <label class="text-sm text-gray-600">Uang Bayar</label>
                <input type="number" id="paid-input" placeholder="0"
                    class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-orange-300"
                    oninput="calcChange()">
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Kembalian</span>
                <span id="change-display" class="font-semibold">Rp 0</span>
            </div>
        </div>

        <button onclick="submitTransaction()" 
            class="w-full mt-4 bg-orange-500 text-white py-3 rounded-xl font-semibold hover:bg-orange-600 transition">
            💳 Proses Transaksi
        </button>
    </div>
</div>

<script>
    let cart = [];

    function addToCart(id, name, price) {
        const existing = cart.find(i => i.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
        renderCart();
    }

    function renderCart() {
        const list = document.getElementById('cart-list');
        const empty = document.getElementById('cart-empty-msg');

        if (cart.length === 0) {
            list.innerHTML = '';
            list.appendChild(empty);
            document.getElementById('total-display').textContent = 'Rp 0';
            return;
        }

        list.innerHTML = cart.map((item, i) => `
            <div class="flex items-center gap-2 text-sm">
                <span class="flex-1 text-gray-700">${item.name}</span>
                <button onclick="updateQty(${i}, -1)" class="w-6 h-6 bg-gray-100 rounded-full text-center">−</button>
                <span class="w-6 text-center">${item.qty}</span>
                <button onclick="updateQty(${i}, 1)" class="w-6 h-6 bg-orange-500 text-white rounded-full text-center">+</button>
                <span class="w-20 text-right font-semibold">Rp ${fmt(item.price * item.qty)}</span>
            </div>
        `).join('');

        const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
        document.getElementById('total-display').textContent = 'Rp ' + fmt(total);
        calcChange();
    }

    function updateQty(index, delta) {
        cart[index].qty += delta;
        if (cart[index].qty <= 0) cart.splice(index, 1);
        renderCart();
    }

    function calcChange() {
        const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
        const paid = parseInt(document.getElementById('paid-input').value) || 0;
        const change = paid - total;
        document.getElementById('change-display').textContent = 'Rp ' + fmt(Math.max(0, change));
    }

    function fmt(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function filterProducts() {
        const q = document.getElementById('search').value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(el => {
            el.style.display = el.dataset.name.includes(q) ? 'block' : 'none';
        });
    }

    function submitTransaction() {
        if (cart.length === 0) return alert('Keranjang kosong!');
        const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
        const paid = parseInt(document.getElementById('paid-input').value) || 0;
        if (paid < total) return alert('Uang bayar kurang!');

        fetch('{{ route("kasir.transaction.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ items: cart, paid, _token: '{{ csrf_token() }}' })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert('✅ Transaksi berhasil!');
                cart = [];
                renderCart();
                document.getElementById('paid-input').value = '';
            }
        });
    }
</script>

@endsection