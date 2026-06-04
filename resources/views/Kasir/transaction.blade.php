@extends('layouts.app')

@section('title', 'Pesanan Offline - Kasir')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">💳 Pesanan Offline</h2>
    <a href="{{ route('kasir.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Kiri: Pilih Produk --}}
    <div>
        <input type="text" id="search" placeholder="🔍 Cari produk..."
            oninput="filterProducts()"
            class="w-full border rounded-xl px-4 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">

        {{-- Filter Kategori --}}
        <div class="flex gap-2 mb-4 overflow-x-auto pb-1">
            <button onclick="filterKategori('semua', this)"
                class="kat-btn px-3 py-1 rounded-full bg-orange-500 text-white text-xs whitespace-nowrap">
                Semua
            </button>
            @foreach(\App\Models\Category::all() as $cat)
            <button onclick="filterKategori('{{ $cat->id }}', this)"
                class="kat-btn px-3 py-1 rounded-full bg-white text-gray-600 text-xs whitespace-nowrap shadow border">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        <div class="grid grid-cols-2 gap-3 max-h-[500px] overflow-y-auto pr-1" id="product-list">
            @foreach($products as $product)
            <div class="product-item bg-white rounded-xl shadow p-3 cursor-pointer hover:shadow-md hover:border-orange-300 border border-transparent transition"
                data-name="{{ strtolower($product->name) }}"
                data-category="{{ $product->category_id }}"
                onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})">

                <div class="bg-orange-50 rounded-lg h-20 flex items-center justify-center mb-2 text-3xl">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-cover rounded-lg">
                    @else
                        🍽️
                    @endif
                </div>

                <p class="font-semibold text-sm text-gray-800 truncate">{{ $product->name }}</p>
                <p class="text-orange-500 text-sm font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-gray-400 text-xs">Stok: {{ $product->stock }}</p>

                @if($product->stock <= 0)
                    <span class="block mt-1 text-center text-xs bg-red-100 text-red-500 py-0.5 rounded-lg">Habis</span>
                @else
                    <span class="block mt-1 text-center text-xs bg-orange-500 text-white py-0.5 rounded-lg">+ Tambah</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Kanan: Nota --}}
    <div class="bg-white rounded-2xl shadow p-5 flex flex-col h-fit sticky top-24">
        <h3 class="font-bold text-gray-800 mb-1 text-lg">🧾 Nota Transaksi</h3>
        <p class="text-gray-400 text-xs mb-4">{{ now()->format('d/m/Y H:i') }}</p>

        {{-- Cart Items --}}
        <div id="cart-list" class="space-y-2 mb-4 max-h-64 overflow-y-auto min-h-[60px]">
            <p id="cart-empty" class="text-gray-400 text-sm text-center py-6">Belum ada item dipilih</p>
        </div>

        <div class="border-t pt-3 space-y-2 mb-4">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span>
                <span id="subtotal-display">Rp 0</span>
            </div>
            <div class="flex justify-between font-bold text-orange-500 text-lg">
                <span>Total</span>
                <span id="total-display">Rp 0</span>
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div class="mb-4">
            <label class="text-sm text-gray-600 mb-2 block font-semibold">Metode Pembayaran</label>
            <div class="grid grid-cols-3 gap-2">
                <button onclick="setMetode('Cash', this)"
                    class="metode-btn border-2 border-orange-500 bg-orange-50 text-orange-600 py-2 rounded-xl text-sm font-semibold">
                    💵 Cash
                </button>
                <button onclick="setMetode('QRIS', this)"
                    class="metode-btn border border-gray-200 text-gray-600 py-2 rounded-xl text-sm hover:border-orange-300">
                    📱 QRIS
                </button>
                <button onclick="setMetode('Debit', this)"
                    class="metode-btn border border-gray-200 text-gray-600 py-2 rounded-xl text-sm hover:border-orange-300">
                    💳 Debit
                </button>
            </div>
        </div>

        {{-- Uang Bayar (hanya Cash) --}}
        <div id="cash-section" class="mb-4">
            <label class="text-sm text-gray-600 mb-1 block">Uang Bayar</label>
            <input type="number" id="paid-input" placeholder="0"
                class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300 text-lg font-bold"
                oninput="calcChange()">

            {{-- Nominal Cepat --}}
            <div class="grid grid-cols-3 gap-2 mt-2" id="nominal-buttons">
                <button onclick="setNominal(10000)" class="border rounded-lg py-1 text-xs text-gray-600 hover:bg-orange-50 hover:border-orange-300">Rp 10.000</button>
                <button onclick="setNominal(20000)" class="border rounded-lg py-1 text-xs text-gray-600 hover:bg-orange-50 hover:border-orange-300">Rp 20.000</button>
                <button onclick="setNominal(50000)" class="border rounded-lg py-1 text-xs text-gray-600 hover:bg-orange-50 hover:border-orange-300">Rp 50.000</button>
                <button onclick="setNominal(100000)" class="border rounded-lg py-1 text-xs text-gray-600 hover:bg-orange-50 hover:border-orange-300">Rp 100.000</button>
                <button onclick="setNominal(50000)" class="border rounded-lg py-1 text-xs text-gray-600 hover:bg-orange-50 hover:border-orange-300">Rp 50.000</button>
                <button onclick="setNominalPas()" class="border rounded-lg py-1 text-xs bg-orange-50 text-orange-500 hover:bg-orange-100 border-orange-200">Uang Pas</button>
            </div>

            <div class="flex justify-between text-sm mt-3 p-3 bg-gray-50 rounded-xl">
                <span class="text-gray-600">Kembalian</span>
                <span id="change-display" class="font-bold text-green-600 text-lg">Rp 0</span>
            </div>
        </div>

        <button onclick="submitTransaction()"
            class="w-full bg-orange-500 text-white py-3 rounded-xl font-bold text-lg hover:bg-orange-600 transition">
            ✅ Proses Transaksi
        </button>

        <button onclick="clearCart()"
            class="w-full mt-2 border border-gray-200 text-gray-400 py-2 rounded-xl text-sm hover:bg-gray-50">
            🗑️ Kosongkan Nota
        </button>
    </div>
</div>

{{-- Modal Sukses --}}
<div id="sukses-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 text-center shadow-xl">
        <div class="text-6xl mb-3">🎉</div>
        <h3 class="text-xl font-bold text-gray-800 mb-1">Transaksi Berhasil!</h3>
        <p class="text-gray-400 text-sm mb-2">Total: <span id="modal-total" class="font-bold text-orange-500"></span></p>
        <p class="text-gray-400 text-sm mb-6">Kembalian: <span id="modal-change" class="font-bold text-green-600"></span></p>
        <div class="flex gap-3">
            <button onclick="closeSuksesModal()"
                class="flex-1 border border-gray-200 text-gray-600 py-2 rounded-xl hover:bg-gray-50">
                Tutup
            </button>
            <button onclick="cetakStruk()"
                class="flex-1 bg-orange-500 text-white py-2 rounded-xl hover:bg-orange-600">
                🖨️ Cetak Struk
            </button>
        </div>
    </div>
</div>

<script>
let cart = [];
let metode = 'Cash';
let lastTransactionId = null;

function addToCart(id, name, price, stock) {
    if (stock <= 0) return;
    const existing = cart.find(i => i.id === id);
    if (existing) {
        if (existing.qty >= stock) {
            alert('Stok tidak mencukupi!');
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
    const empty = document.getElementById('cart-empty');

    if (cart.length === 0) {
        list.innerHTML = '<p id="cart-empty" class="text-gray-400 text-sm text-center py-6">Belum ada item dipilih</p>';
        document.getElementById('total-display').textContent = 'Rp 0';
        document.getElementById('subtotal-display').textContent = 'Rp 0';
        return;
    }

    list.innerHTML = cart.map((item, i) => `
        <div class="flex items-center gap-2 py-1">
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800 truncate">${item.name}</p>
                <p class="text-xs text-orange-500">Rp ${fmt(item.price)} × ${item.qty}</p>
            </div>
            <div class="flex items-center gap-1">
                <button onclick="updateQty(${i}, -1)" class="w-7 h-7 bg-gray-100 rounded-full text-sm hover:bg-red-100 hover:text-red-500">−</button>
                <span class="w-6 text-center text-sm font-bold">${item.qty}</span>
                <button onclick="updateQty(${i}, 1)" class="w-7 h-7 bg-orange-500 text-white rounded-full text-sm hover:bg-orange-600">+</button>
            </div>
            <span class="text-sm font-bold text-gray-700 w-20 text-right">Rp ${fmt(item.price * item.qty)}</span>
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
        b.classList.remove('border-2', 'border-orange-500', 'bg-orange-50', 'text-orange-600');
        b.classList.add('border', 'border-gray-200', 'text-gray-600');
    });
    btn.classList.add('border-2', 'border-orange-500', 'bg-orange-50', 'text-orange-600');
    btn.classList.remove('border', 'border-gray-200', 'text-gray-600');
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
        b.classList.remove('bg-orange-500', 'text-white');
        b.classList.add('bg-white', 'text-gray-600');
    });
    btn.classList.add('bg-orange-500', 'text-white');
    btn.classList.remove('bg-white', 'text-gray-600');
}

function submitTransaction() {
    if (cart.length === 0) return alert('Pilih produk dulu!');

    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const paid = metode === 'Cash' ? (parseInt(document.getElementById('paid-input').value) || 0) : total;

    if (metode === 'Cash' && paid < total) return alert('Uang bayar kurang!');

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
            alert('Transaksi gagal, coba lagi!');
        }
    })
    .catch(() => alert('Terjadi kesalahan!'));
}

function closeSuksesModal() {
    document.getElementById('sukses-modal').classList.add('hidden');
}

function cetakStruk() {
    alert('Fitur cetak struk coming soon!');
    closeSuksesModal();
}
</script>

@endsection