@extends('layouts.app')

@section('title', 'Pembayaran - Naliko Warung')

@section('content')

<div class="max-w-lg mx-auto">

    {{-- Alert jika ditolak --}}
    @if($order->payment_status === 'rejected')
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4 flex gap-3">
        <span class="text-2xl">❌</span>
        <div>
            <p class="font-semibold text-red-600">Bukti pembayaran ditolak</p>
            <p class="text-sm text-red-500 mt-1">{{ $order->payment_note ?? 'Silakan upload ulang bukti yang valid.' }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow p-6">

        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="text-5xl mb-3">💳</div>
            <h2 class="text-xl font-bold text-gray-800">Selesaikan Pembayaran</h2>
            <p class="text-gray-400 text-sm mt-1">Order #{{ $order->id }} · {{ $order->customer_name }}</p>
        </div>

        {{-- Ringkasan Pesanan --}}
        <div class="bg-gray-50 rounded-xl p-4 mb-5">
            <h3 class="font-semibold text-gray-700 mb-3 text-sm">Ringkasan Pesanan</h3>
            <div class="space-y-2">
                @foreach($order->items as $item)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ $item->product->name }} × {{ $item->qty }}</span>
                    <span>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t mt-3 pt-3 flex justify-between font-bold text-orange-500">
                <span>Total Bayar</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Info QRIS / Rekening --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-5">
            <h3 class="font-semibold text-orange-700 mb-3 text-sm">Informasi Pembayaran</h3>
            <div class="space-y-2 text-sm text-gray-700">
                <div class="flex gap-2 items-center">
                    <span>📱</span>
                    <div>
                        <p class="font-semibold">QRIS</p>
                        <p class="text-gray-400 text-xs">Scan QR di kasir</p>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <span>🏦</span>
                    <div>
                        <p class="font-semibold">Transfer BCA · 1234567890</p>
                        <p class="text-gray-400 text-xs">a.n. Naliko Warung</p>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <span>💵</span>
                    <div>
                        <p class="font-semibold">Cash</p>
                        <p class="text-gray-400 text-xs">Bayar langsung ke kasir</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Upload Bukti --}}
        <form action="{{ route('order.payment.upload', $order) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Pilih Metode --}}
            <div class="mb-4">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">Metode Pembayaran</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['QRIS', 'Transfer', 'Cash'] as $method)
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="{{ $method }}"
                            class="hidden peer" {{ old('payment_method') === $method ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl py-2 text-center text-sm font-semibold
                            peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-600
                            border-gray-200 text-gray-500 hover:border-orange-300 transition">
                            {{ $method === 'QRIS' ? '📱' : ($method === 'Transfer' ? '🏦' : '💵') }}
                            {{ $method }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('payment_method')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload Foto --}}
            <div class="mb-5" id="upload-section">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">
                    Upload Bukti Pembayaran
                </label>
                <label class="block border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer
                    hover:border-orange-400 hover:bg-orange-50 transition" id="upload-label">
                    <input type="file" name="payment_proof" accept="image/*" class="hidden" id="proof-input"
                        onchange="previewImage(this)">
                    <div id="upload-placeholder">
                        <p class="text-3xl mb-2">📸</p>
                        <p class="text-gray-400 text-sm">Klik untuk pilih foto</p>
                        <p class="text-gray-300 text-xs mt-1">JPG, PNG · Maks 2MB</p>
                    </div>
                    <img id="preview-img" src="" alt="Preview" class="hidden mx-auto max-h-48 rounded-lg">
                </label>
                @error('payment_proof')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-orange-500 text-white py-3 rounded-xl font-bold text-lg hover:bg-orange-600 transition">
                📤 Kirim Bukti Pembayaran
            </button>
        </form>

    </div>
</div>

<script>
function previewImage(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('upload-placeholder').classList.add('hidden');
        const img = document.getElementById('preview-img');
        img.src = e.target.result;
        img.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const uploadDiv = document.getElementById('upload-section');
        if (this.value === 'Cash') {
            uploadDiv.classList.add('hidden');
            document.getElementById('proof-input').value = ""; // clear
        } else {
            uploadDiv.classList.remove('hidden');
        }
    });
});
</script>

@endsection