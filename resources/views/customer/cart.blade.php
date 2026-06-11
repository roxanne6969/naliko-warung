@extends('layouts.app')

@section('title', 'Keranjang - Naliko Warung')

@section('content')

<div class="flex items-center gap-3 mb-6">
    @svg('heroicon-o-shopping-cart', 'w-6 h-6 text-[#5C4A35]')
    <h2 class="text-2xl font-bold text-[#3E2F1E]">Keranjang Belanja</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- List Item --}}
    <div class="md:col-span-2">
        <div id="cart-items" class="space-y-3">
            {{-- Diisi oleh JavaScript --}}
        </div>
        <div id="cart-empty" class="hidden bg-white rounded-2xl border border-[#e8d5c1] p-10 text-center shadow-sm">
            <div class="w-16 h-16 bg-[#f5e6d3] rounded-full flex items-center justify-center mx-auto mb-4">
                @svg('heroicon-o-shopping-cart', 'w-8 h-8 text-[#c4a882]')
            </div>
            <p class="font-semibold text-[#3E2F1E] mb-1">Keranjang masih kosong</p>
            <p class="text-[#9e8065] text-sm mb-4">Yuk tambah menu favoritmu!</p>
            <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 bg-[#5C4A35] text-[#F7E6CC] px-6 py-2.5 rounded-xl font-semibold hover:bg-[#3E2F1E] transition text-sm">
                @svg('heroicon-o-arrow-left', 'w-4 h-4') Lihat Menu
            </a>
        </div>
    </div>

    {{-- Summary --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 h-fit sticky top-24">
        <div class="flex items-center gap-2 mb-4">
            @svg('heroicon-o-receipt-percent', 'w-5 h-5 text-[#5C4A35]')
            <h3 class="font-bold text-[#3E2F1E]">Ringkasan Pesanan</h3>
        </div>

        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm text-[#9e8065]">
                <span>Total Item</span>
                <span id="summary-count" class="font-semibold text-[#3E2F1E]">0</span>
            </div>
        </div>

        <div class="flex justify-between font-bold text-lg text-[#5C4A35] border-t border-[#f0e0cc] pt-3 mb-5">
            <span>Total</span>
            <span id="summary-total">Rp 0</span>
        </div>

        <button onclick="checkout()"
            class="w-full flex items-center justify-center gap-2 bg-[#5C4A35] text-[#F7E6CC] py-3 rounded-xl font-bold hover:bg-[#3E2F1E] transition">
            @svg('heroicon-o-arrow-right', 'w-4 h-4') Lanjut Pesan
        </button>
        <a href="{{ route('menu.index') }}"
            class="flex items-center justify-center gap-1 mt-3 text-[#9e8065] text-sm hover:text-[#5C4A35] transition">
            @svg('heroicon-o-arrow-left', 'w-3.5 h-3.5') Tambah Menu Lagi
        </a>
    </div>
</div>

{{-- Modal Form Pesanan --}}
<div id="order-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-xl overflow-hidden border border-[#e8d5c1]">
        <div class="bg-[#5C4A35] px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                @svg('heroicon-o-clipboard-document-list', 'w-5 h-5 text-[#F7E6CC]')
                <h3 class="text-[#F7E6CC] font-bold text-lg">Detail Pesanan</h3>
            </div>
            <button onclick="closeModal()" class="text-[#F7E6CC] hover:text-[#dbc7a9]">
                @svg('heroicon-o-x-mark', 'w-5 h-5')
            </button>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="text-sm font-semibold text-[#3E2F1E] mb-1.5 block flex items-center gap-1">
                    @svg('heroicon-o-user', 'w-4 h-4 text-[#9e8065]') Nama Pemesan
                </label>
                <input type="text" id="customer-name" placeholder="Masukkan nama kamu"
                    class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            </div>
            <div>
                <label class="text-sm font-semibold text-[#3E2F1E] mb-1.5 block flex items-center gap-1">
                    @svg('heroicon-o-table-cells', 'w-4 h-4 text-[#9e8065]') Nomor Meja <span class="text-[#9e8065] font-normal">(opsional)</span>
                </label>
                <input type="text" id="table-number" placeholder="Contoh: Meja 3"
                    class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            </div>
            <div>
                <label class="text-sm font-semibold text-[#3E2F1E] mb-1.5 block flex items-center gap-1">
                    @svg('heroicon-o-document-text', 'w-4 h-4 text-[#9e8065]') Catatan <span class="text-[#9e8065] font-normal">(opsional)</span>
                </label>
                <textarea id="order-note" placeholder="Contoh: Tidak pakai pedas"
                    class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E] resize-none" rows="3"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button onclick="closeModal()"
                    class="flex-1 border border-[#e8d5c1] text-[#9e8065] py-2.5 rounded-xl hover:bg-[#fdf5ec] transition">
                    Batal
                </button>
                <button onclick="submitOrder()"
                    class="flex-1 flex items-center justify-center gap-2 bg-[#5C4A35] text-[#F7E6CC] py-2.5 rounded-xl hover:bg-[#3E2F1E] transition font-semibold">
                    @svg('heroicon-o-check', 'w-4 h-4') Pesan Sekarang
                </button>
            </div>
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
            <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-4 flex items-center gap-4">
                <div class="bg-[#f5e6d3] rounded-xl w-14 h-14 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#c4a882]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" /></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-[#3E2F1E]">${item.name}</h4>
                    <p class="text-[#5C4A35] text-sm font-bold">Rp ${formatRupiah(item.price)}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="updateQty(${index}, -1)"
                        class="w-8 h-8 bg-[#f5e6d3] rounded-full text-[#5C4A35] hover:bg-red-100 hover:text-red-500 font-bold transition">−</button>
                    <span class="w-6 text-center font-bold text-[#3E2F1E]">${item.qty}</span>
                    <button onclick="updateQty(${index}, 1)"
                        class="w-8 h-8 bg-[#5C4A35] rounded-full text-[#F7E6CC] hover:bg-[#3E2F1E] font-bold transition">+</button>
                </div>
                <p class="font-bold text-[#5C4A35] w-24 text-right">Rp ${formatRupiah(item.price * item.qty)}</p>
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
                window.location.href = '/order/' + res.order_id + '/payment';
            } else {
                alert('Gagal memesan, coba lagi!');
            }
        })
        .catch(() => alert('Terjadi kesalahan!'));
    }

    document.addEventListener('click', function(e) {
        if (e.target === document.getElementById('order-modal')) closeModal();
    });
</script>

@endsection