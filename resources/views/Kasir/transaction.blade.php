@extends('layouts.app')

@section('title', 'Pesanan Offline - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-document-text', 'w-6 h-6 text-[#5C4A35]')
        <div>
            <h2 class="text-2xl font-bold text-[#3E2F1E]">Pesanan Offline</h2>
            <p class="text-[#9e8065] text-sm">Transaksi langsung di kasir</p>
        </div>
    </div>
    <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-1 text-[#9e8065] hover:text-[#5C4A35] text-sm transition">
        @svg('heroicon-o-arrow-left', 'w-4 h-4') Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Kiri: Pilih Produk --}}
    <div>
        <div class="relative mb-3">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                @svg('heroicon-o-magnifying-glass', 'w-4 h-4 text-[#9e8065]')
            </div>
            <input type="text" id="search" placeholder="Cari produk..."
                oninput="filterProducts()"
                class="w-full border border-[#e8d5c1] rounded-xl pl-9 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-white text-[#3E2F1E]">
        </div>

        {{-- Filter Kategori --}}
        <div class="flex gap-2 mb-4 overflow-x-auto pb-1">
            <button onclick="filterKategori('semua', this)"
                class="kat-btn px-3 py-1.5 rounded-full bg-[#5C4A35] text-[#F7E6CC] text-xs whitespace-nowrap font-semibold">
                Semua
            </button>
            @foreach(\App\Models\Category::all() as $cat)
            <button onclick="filterKategori('{{ $cat->id }}', this)"
                class="kat-btn px-3 py-1.5 rounded-full bg-white text-[#9e8065] text-xs whitespace-nowrap border border-[#e8d5c1] hover:border-[#5C4A35] transition">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        <div class="grid grid-cols-2 gap-3 max-h-[500px] overflow-y-auto pr-1" id="product-list">
            @foreach($products as $product)
            <div class="product-item bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-3 cursor-pointer hover:shadow-md hover:border-[#5C4A35] transition"
                data-name="{{ strtolower($product->name) }}"
                data-category="{{ $product->category_id }}"
                onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})">

                <div class="bg-[#f5e6d3] rounded-xl h-20 flex items-center justify-center mb-2 overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-cover rounded-xl">
                    @else
                        @svg('heroicon-o-photo', 'w-8 h-8 text-[#c4a882]')
                    @endif
                </div>

                <p class="font-semibold text-sm text-[#3E2F1E] truncate">{{ $product->name }}</p>
                <p class="text-[#5C4A35] text-sm font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-[#9e8065] text-xs">Stok: {{ $product->stock }}</p>

                @if($product->stock <= 0)
                    <span class="block mt-1.5 text-center text-xs bg-red-100 text-red-500 py-1 rounded-lg font-semibold">Habis</span>
                @else
                    <span class="block mt-1.5 text-center text-xs bg-[#5C4A35] text-[#F7E6CC] py-1 rounded-lg font-semibold">+ Tambah</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Kanan: Nota --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-5 flex flex-col h-fit sticky top-24">
        <div class="flex items-center gap-2 mb-1">
            @svg('heroicon-o-receipt-percent', 'w-5 h-5 text-[#5C4A35]')
            <h3 class="font-bold text-[#3E2F1E] text-lg">Nota Transaksi</h3>
        </div>
        <p class="text-[#9e8065] text-xs mb-4">{{ now()->format('d/m/Y H:i') }}</p>

        {{-- Cart Items --}}
        <div id="cart-list" class="space-y-2 mb-4 max-h-64 overflow-y-auto min-h-[60px]">
            <p id="cart-empty" class="text-[#9e8065] text-sm text-center py-6">Belum ada item dipilih</p>
        </div>

        <div class="border-t border-[#f0e0cc] pt-3 space-y-2 mb-4">
            <div class="flex justify-between text-sm text-[#9e8065]">
                <span>Subtotal</span>
                <span id="subtotal-display">Rp 0</span>
            </div>
            <div class="flex justify-between font-bold text-[#5C4A35] text-lg">
                <span>Total</span>
                <span id="total-display">Rp 0</span>
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div class="mb-4">
            <label class="text-sm text-[#5C4A35] mb-2 block font-semibold">Metode Pembayaran</label>
            <div class="grid grid-cols-3 gap-2">
                <button onclick="setMetode('Cash', this)"
                    class="metode-btn border-2 border-[#5C4A35] bg-[#f5e6d3] text-[#5C4A35] py-2 rounded-xl text-xs font-semibold flex flex-col items-center gap-1">
                    @svg('heroicon-o-banknotes', 'w-4 h-4') Cash
                </button>
                <button onclick="setMetode('QRIS', this)"
                    class="metode-btn border border-[#e8d5c1] text-[#9e8065] py-2 rounded-xl text-xs hover:border-[#5C4A35] transition flex flex-col items-center gap-1">
                    @svg('heroicon-o-qr-code', 'w-4 h-4') QRIS
                </button>
            </div>
        </div>

        {{-- Uang Bayar (hanya Cash) --}}
        <div id="cash-section" class="mb-4">
            <label class="text-sm text-[#5C4A35] mb-1 block font-semibold">Uang Bayar</label>
            <input type="number" id="paid-input" placeholder="0"
                class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] text-lg font-bold text-[#3E2F1E] bg-[#fdfaf7]"
                oninput="calcChange()">

            {{-- Nominal Cepat --}}
            <div class="grid grid-cols-3 gap-2 mt-2" id="nominal-buttons">
                <button onclick="setNominal(10000)" class="border border-[#e8d5c1] rounded-lg py-1 text-xs text-[#9e8065] hover:bg-[#f5e6d3] hover:border-[#5C4A35] hover:text-[#5C4A35] transition">Rp 10.000</button>
                <button onclick="setNominal(20000)" class="border border-[#e8d5c1] rounded-lg py-1 text-xs text-[#9e8065] hover:bg-[#f5e6d3] hover:border-[#5C4A35] hover:text-[#5C4A35] transition">Rp 20.000</button>
                <button onclick="setNominal(50000)" class="border border-[#e8d5c1] rounded-lg py-1 text-xs text-[#9e8065] hover:bg-[#f5e6d3] hover:border-[#5C4A35] hover:text-[#5C4A35] transition">Rp 50.000</button>
                <button onclick="setNominal(100000)" class="border border-[#e8d5c1] rounded-lg py-1 text-xs text-[#9e8065] hover:bg-[#f5e6d3] hover:border-[#5C4A35] hover:text-[#5C4A35] transition">Rp 100.000</button>
                <button onclick="setNominal(200000)" class="border border-[#e8d5c1] rounded-lg py-1 text-xs text-[#9e8065] hover:bg-[#f5e6d3] hover:border-[#5C4A35] hover:text-[#5C4A35] transition">Rp 200.000</button>
                <button onclick="setNominalPas()" class="border border-[#5C4A35] rounded-lg py-1 text-xs bg-[#f5e6d3] text-[#5C4A35] hover:bg-[#e8d5c1] transition font-semibold">Uang Pas</button>
            </div>

            <div class="flex justify-between text-sm mt-3 p-3 bg-[#fdfaf7] rounded-xl border border-[#f0e0cc]">
                <span class="text-[#9e8065]">Kembalian</span>
                <span id="change-display" class="font-bold text-green-600 text-lg">Rp 0</span>
            </div>
        </div>

        <button onclick="submitTransaction()"
            class="w-full flex items-center justify-center gap-2 bg-[#5C4A35] text-[#F7E6CC] py-3 rounded-xl font-bold text-lg hover:bg-[#3E2F1E] transition">
            @svg('heroicon-o-check-circle', 'w-5 h-5') Proses Transaksi
        </button>

        <button onclick="clearCart()"
            class="w-full mt-2 flex items-center justify-center gap-1 border border-[#e8d5c1] text-[#9e8065] py-2 rounded-xl text-sm hover:bg-[#fdf5ec] hover:text-red-500 hover:border-red-200 transition">
            @svg('heroicon-o-trash', 'w-4 h-4') Kosongkan Nota
        </button>
    </div>
</div>

{{-- Modal Sukses --}}
<div id="sukses-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 text-center shadow-xl border border-[#e8d5c1]">
        <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
            @svg('heroicon-o-check-circle', 'w-8 h-8 text-green-600')
        </div>
        <h3 class="text-xl font-bold text-[#3E2F1E] mb-1">Transaksi Berhasil!</h3>
        <p class="text-[#9e8065] text-sm mb-1">Total: <span id="modal-total" class="font-bold text-[#5C4A35]"></span></p>
        <p class="text-[#9e8065] text-sm mb-6">Kembalian: <span id="modal-change" class="font-bold text-green-600"></span></p>
        <div class="flex">
            <button onclick="closeSuksesModal()"
                class="w-full border border-[#e8d5c1] text-[#9e8065] py-2.5 rounded-xl hover:bg-[#fdf5ec] transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
let cart = [];
let metode = 'Cash';
let lastTransactionId = null;

function addToCart(id, name, price, stock) {
    if (stock <= 0) {
        alert('Maaf, stok ' + name + ' sudah habis!');
        return;
    }
    const existing = cart.find(i => i.id === id);
    if (existing) {
        if (existing.qty >= stock) {
            alert('Stok ' + name + ' hanya tersisa ' + stock + '!');
            return;
        }
        existing.qty++;
    } else {
        cart.push({ id, name, price, qty: 1, stock });
    }
    renderCart();
}

function renderCart() {
    const list = document.getElementById('cart-list');

    if (cart.length === 0) {
        list.innerHTML = '<p id="cart-empty" class="text-[#9e8065] text-sm text-center py-6">Belum ada item dipilih</p>';
        document.getElementById('total-display').textContent = 'Rp 0';
        document.getElementById('subtotal-display').textContent = 'Rp 0';
        return;
    }

    list.innerHTML = cart.map((item, i) => `
        <div class="flex items-center gap-2 py-1.5 border-b border-[#f0e0cc] last:border-0">
            <div class="flex-1">
                <p class="text-sm font-semibold text-[#3E2F1E] truncate">${item.name}</p>
                <p class="text-xs text-[#9e8065]">Rp ${fmt(item.price)} × ${item.qty}</p>
            </div>
            <div class="flex items-center gap-1">
                <button onclick="updateQty(${i}, -1)" class="w-7 h-7 bg-[#f5e6d3] rounded-full text-sm hover:bg-red-100 hover:text-red-500 text-[#5C4A35] font-bold">−</button>
                <span class="w-6 text-center text-sm font-bold text-[#3E2F1E]">${item.qty}</span>
                <button onclick="updateQty(${i}, 1)" class="w-7 h-7 bg-[#5C4A35] text-[#F7E6CC] rounded-full text-sm hover:bg-[#3E2F1E] font-bold">+</button>
            </div>
            <span class="text-sm font-bold text-[#5C4A35] w-20 text-right">Rp ${fmt(item.price * item.qty)}</span>
        </div>
    `).join('');

    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    document.getElementById('total-display').textContent = 'Rp ' + fmt(total);
    document.getElementById('subtotal-display').textContent = 'Rp ' + fmt(total);
    calcChange();
}

function updateQty(index, delta) {
    cart[index].qty += delta;
    if (cart[index].qty <= 0) cart.splice(index, 1);
    renderCart();
}

function clearCart() {
    if (cart.length === 0) return;
    if (confirm('Kosongkan nota?')) {
        cart = [];
        renderCart();
        document.getElementById('paid-input').value = '';
        document.getElementById('change-display').textContent = 'Rp 0';
    }
}

function setMetode(m, btn) {
    metode = m;
    document.querySelectorAll('.metode-btn').forEach(b => {
        b.classList.remove('border-2', 'border-[#5C4A35]', 'bg-[#f5e6d3]', 'text-[#5C4A35]');
        b.classList.add('border', 'border-[#e8d5c1]', 'text-[#9e8065]');
    });
    btn.classList.add('border-2', 'border-[#5C4A35]', 'bg-[#f5e6d3]', 'text-[#5C4A35]');
    btn.classList.remove('border', 'border-[#e8d5c1]', 'text-[#9e8065]');
    document.getElementById('cash-section').style.display = m === 'Cash' ? 'block' : 'none';
}

function setNominal(val) {
    document.getElementById('paid-input').value = val;
    calcChange();
}

function setNominalPas() {
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    document.getElementById('paid-input').value = total;
    calcChange();
}

function calcChange() {
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const paid = parseInt(document.getElementById('paid-input').value) || 0;
    const change = paid - total;
    document.getElementById('change-display').textContent = 'Rp ' + fmt(Math.max(0, change));
    document.getElementById('change-display').style.color = change < 0 ? '#ef4444' : '#16a34a';
}

function fmt(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function filterProducts() {
    const q = document.getElementById('search').value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(el => {
        el.style.display = el.dataset.name.includes(q) ? '' : 'none';
    });
}

function filterKategori(id, btn) {
    document.querySelectorAll('.product-item').forEach(el => {
        el.style.display = (id === 'semua' || el.dataset.category == id) ? '' : 'none';
    });
    document.querySelectorAll('.kat-btn').forEach(b => {
        b.classList.remove('bg-[#5C4A35]', 'text-[#F7E6CC]');
        b.classList.add('bg-white', 'text-[#9e8065]', 'border', 'border-[#e8d5c1]');
    });
    btn.classList.add('bg-[#5C4A35]', 'text-[#F7E6CC]');
    btn.classList.remove('bg-white', 'text-[#9e8065]', 'border', 'border-[#e8d5c1]');
}

function submitTransaction() {
    if (cart.length === 0) return alert('Pilih produk dulu!');

    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const paid = metode === 'Cash' ? (parseInt(document.getElementById('paid-input').value) || 0) : total;

    if (metode === 'Cash' && paid < total) return alert('Uang bayar kurang!');

    const outOfStock = cart.filter(i => i.qty > i.stock);
    if (outOfStock.length > 0) {
        alert('Stok tidak mencukupi untuk: ' + outOfStock.map(i => i.name).join(', '));
        return;
    }

    fetch('{{ route("kasir.transaction.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ items: cart, paid, metode })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            lastTransactionId = res.transaction_id;
            document.getElementById('modal-total').textContent = 'Rp ' + fmt(total);
            document.getElementById('modal-change').textContent = 'Rp ' + fmt(Math.max(0, paid - total));
            document.getElementById('sukses-modal').classList.remove('hidden');
            cart = [];
            renderCart();
            document.getElementById('paid-input').value = '';
        } else {
            alert('❌ ' + res.message);
        }
    })
    .catch(() => alert('Terjadi kesalahan!'));
}

function closeSuksesModal() {
    document.getElementById('sukses-modal').classList.add('hidden');
}

</script>

@endsection