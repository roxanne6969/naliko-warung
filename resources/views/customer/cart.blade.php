@extends('layouts.app')

@section('title', 'Keranjang - Naliko Warung')

@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">🛒 Keranjang Belanja</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- List Item --}}
    <div class="md:col-span-2">
        <div id="cart-items" class="space-y-4">
            {{-- Diisi oleh JavaScript --}}
        </div>
        <div id="cart-empty" class="hidden bg-white rounded-xl p-10 text-center text-gray-400">
            <p class="text-4xl mb-3">🛒</p>
            <p>Keranjang masih kosong</p>
            <a href="{{ route('menu.index') }}" class="mt-4 inline-block bg-orange-500 text-white px-6 py-2 rounded-lg">
                Lihat Menu
            </a>
        </div>
    </div>

    {{-- Summary --}}
    <div class="bg-white rounded-xl shadow p-6 h-fit">
        <h3 class="font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>
        <div class="flex justify-between text-gray-600 mb-2">
            <span>Total Item</span>
            <span id="summary-count">0</span>
        </div>
        <div class="flex justify-between font-bold text-lg text-orange-500 border-t pt-3">
            <span>Total</span>
            <span id="summary-total">Rp 0</span>
        </div>

        <button onclick="checkout()" 
            class="w-full mt-6 bg-orange-500 text-white py-3 rounded-xl font-semibold hover:bg-orange-600 transition">
            Lanjut Pesan
        </button>
        <a href="{{ route('menu.index') }}" 
            class="block text-center mt-3 text-gray-400 text-sm hover:text-orange-500">
            ← Tambah Menu Lagi
        </a>
    </div>
</div>

{{-- Modal Form Pesanan --}}
<div id="order-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4">📋 Detail Pesanan</h3>
        
        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Nama Pemesan</label>
                <input type="text" id="customer-name" placeholder="Masukkan nama kamu"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Nomor Meja (opsional)</label>
                <input type="text" id="table-number" placeholder="Contoh: Meja 3"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Catatan (opsional)</label>
                <textarea id="order-note" placeholder="Contoh: Tidak pakai pedas"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none" rows="3"></textarea>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button onclick="closeModal()" 
                class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-lg hover:bg-gray-50">
                Batal
            </button>
            <button onclick="submitOrder()" 
                class="flex-1 bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600">
                Pesan Sekarang
            </button>
        </div>
    </div>
</div>

<script>
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    renderCart();

    function renderCart() {
        const container = document.getElementById('cart-items');
        const empty = document.getElementById('cart-empty');

        if (cart.length === 0) {
            container.classList.add('hidden');
            empty.classList.remove('hidden');
            return;
        }

        container.classList.remove('hidden');
        empty.classList.add('hidden');

        container.innerHTML = cart.map((item, index) => `
            <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                <div class="bg-orange-100 rounded-lg w-16 h-16 flex items-center justify-center text-2xl flex-shrink-0">
                    🍽️
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800">${item.name}</h4>
                    <p class="text-orange-500 text-sm">Rp ${formatRupiah(item.price)}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="updateQty(${index}, -1)" 
                        class="w-8 h-8 bg-gray-100 rounded-full text-gray-600 hover:bg-orange-100 font-bold">−</button>
                    <span class="w-6 text-center font-semibold">${item.qty}</span>
                    <button onclick="updateQty(${index}, 1)" 
                        class="w-8 h-8 bg-orange-500 rounded-full text-white hover:bg-orange-600 font-bold">+</button>
                </div>
                <p class="font-bold text-gray-800 w-24 text-right">Rp ${formatRupiah(item.price * item.qty)}</p>
            </div>
        `).join('');

        updateSummary();
    }

    function updateQty(index, delta) {
        cart[index].qty += delta;
        if (cart[index].qty <= 0) cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }

    function updateSummary() {
        const count = cart.reduce((sum, i) => sum + i.qty, 0);
        const total = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
        document.getElementById('summary-count').textContent = count + ' item';
        document.getElementById('summary-total').textContent = 'Rp ' + formatRupiah(total);
    }

    function formatRupiah(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function checkout() {
        if (cart.length === 0) return alert('Keranjang masih kosong!');
        document.getElementById('order-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('order-modal').classList.add('hidden');
    }

    function submitOrder() {
        const name = document.getElementById('customer-name').value.trim();
        if (!name) return alert('Nama pemesan harus diisi!');

        const data = {
            customer_name: name,
            table_number: document.getElementById('table-number').value,
            note: document.getElementById('order-note').value,
            items: cart,
            _token: '{{ csrf_token() }}'
        };

        fetch('{{ route("order.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                localStorage.removeItem('cart');
                window.location.href = '/order/' + res.order_id + '/status';
            } else {
                alert('Gagal memesan, coba lagi!');
            }
        })
        .catch(() => alert('Terjadi kesalahan!'));
    }
</script>

@endsection