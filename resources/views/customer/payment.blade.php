@extends('layouts.app')

@section('title', 'Pembayaran - Naliko Warung')

@section('content')

<div class="max-w-lg mx-auto">

    {{-- Alert jika ditolak --}}
    @if($order->payment_status === 'rejected')
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-5 flex gap-3">
        @svg('heroicon-o-x-circle', 'w-5 h-5 text-red-500 flex-shrink-0 mt-0.5')
        <div>
            <p class="font-semibold text-red-600">Bukti pembayaran ditolak</p>
            <p class="text-sm text-red-500 mt-0.5">{{ $order->payment_note ?? 'Silakan upload ulang bukti yang valid.' }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] overflow-hidden">

        {{-- Header --}}
        <div class="bg-[#5C4A35] px-6 py-5 text-center">
            <div class="w-14 h-14 bg-[#F7E6CC] bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                @svg('heroicon-o-credit-card', 'w-7 h-7 text-[#F7E6CC]')
            </div>
            <h2 class="text-xl font-bold text-[#F7E6CC]">Selesaikan Pembayaran</h2>
            <p class="text-[#dbc7a9] text-sm mt-1">Order #{{ $order->id }} · {{ $order->customer_name }}</p>
        </div>

        <div class="p-6">

            {{-- Ringkasan Pesanan --}}
            <div class="bg-[#fdfaf7] rounded-xl border border-[#f0e0cc] p-4 mb-5">
                <p class="text-xs font-semibold text-[#9e8065] uppercase tracking-wide mb-3">Ringkasan Pesanan</p>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-[#3E2F1E]">{{ $item->product->name }} × {{ $item->qty }}</span>
                        <span class="text-[#5C4A35] font-semibold">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-[#f0e0cc] mt-3 pt-3 flex justify-between font-bold text-[#5C4A35]">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Info QRIS / Rekening --}}
            <div class="bg-[#f5e6d3] border border-[#e8d5c1] rounded-xl p-4 mb-5">
                <p class="font-semibold text-[#3E2F1E] mb-3 text-sm flex items-center gap-2">
                    @svg('heroicon-o-information-circle', 'w-4 h-4 text-[#5C4A35]') Informasi Pembayaran
                </p>
                <div class="space-y-2.5 text-sm">
                    <div class="flex gap-3 items-center">
                        @svg('heroicon-o-qr-code', 'w-5 h-5 text-[#5C4A35] flex-shrink-0')
                        <div>
                            <p class="font-semibold text-[#3E2F1E]">QRIS</p>
                            <p class="text-[#9e8065] text-xs">Scan QR di kasir</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-center">
                        @svg('heroicon-o-building-library', 'w-5 h-5 text-[#5C4A35] flex-shrink-0')
                        <div>
                            <p class="font-semibold text-[#3E2F1E]">Transfer BCA · 1234567890</p>
                            <p class="text-[#9e8065] text-xs">a.n. Naliko Warung</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-center">
                        @svg('heroicon-o-banknotes', 'w-5 h-5 text-[#5C4A35] flex-shrink-0')
                        <div>
                            <p class="font-semibold text-[#3E2F1E]">Cash</p>
                            <p class="text-[#9e8065] text-xs">Bayar langsung ke kasir</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Upload Bukti --}}
            <form action="{{ route('order.payment.upload', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Pilih Metode --}}
                <div class="mb-4">
                    <label class="text-sm font-semibold text-[#3E2F1E] mb-2 block">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['QRIS', 'Transfer', 'Cash'] as $method)
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $method }}"
                                class="hidden peer" {{ old('payment_method') === $method ? 'checked' : '' }}>
                            <div class="border-2 rounded-xl py-2.5 text-center text-sm font-semibold
                                peer-checked:border-[#5C4A35] peer-checked:bg-[#f5e6d3] peer-checked:text-[#5C4A35]
                                border-[#e8d5c1] text-[#9e8065] hover:border-[#5C4A35] transition">
                                @if($method === 'QRIS')
                                    <div class="flex flex-col items-center gap-1">
                                        @svg('heroicon-o-qr-code', 'w-4 h-4') {{ $method }}
                                    </div>
                                @elseif($method === 'Transfer')
                                    <div class="flex flex-col items-center gap-1">
                                        @svg('heroicon-o-building-library', 'w-4 h-4') {{ $method }}
                                    </div>
                                @else
                                    <div class="flex flex-col items-center gap-1">
                                        @svg('heroicon-o-banknotes', 'w-4 h-4') {{ $method }}
                                    </div>
                                @endif
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
                    <label class="text-sm font-semibold text-[#3E2F1E] mb-2 block">
                        Upload Bukti Pembayaran
                    </label>
                    <label class="block border-2 border-dashed border-[#e8d5c1] rounded-xl p-6 text-center cursor-pointer
                        hover:border-[#5C4A35] hover:bg-[#fdf5ec] transition" id="upload-label">
                        <input type="file" name="payment_proof" accept="image/*" class="hidden" id="proof-input"
                            onchange="previewImage(this)">
                        <div id="upload-placeholder">
                            <div class="w-12 h-12 bg-[#f5e6d3] rounded-full flex items-center justify-center mx-auto mb-2">
                                @svg('heroicon-o-camera', 'w-6 h-6 text-[#5C4A35]')
                            </div>
                            <p class="text-[#9e8065] text-sm">Klik untuk pilih foto</p>
                            <p class="text-[#c4a882] text-xs mt-1">JPG, PNG · Maks 2MB</p>
                        </div>
                        <img id="preview-img" src="" alt="Preview" class="hidden mx-auto max-h-48 rounded-xl">
                    </label>
                    @error('payment_proof')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-[#5C4A35] text-[#F7E6CC] py-3.5 rounded-xl font-bold text-lg hover:bg-[#3E2F1E] transition">
                    @svg('heroicon-o-paper-airplane', 'w-5 h-5') Kirim Bukti Pembayaran
                </button>
            </form>

        </div>
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
            document.getElementById('proof-input').value = "";
        } else {
            uploadDiv.classList.remove('hidden');
        }
    });
});
</script>

@endsection