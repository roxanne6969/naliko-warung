@extends('layouts.app')

@section('title', 'Pengaturan Sistem - Admin')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-cog-8-tooth', 'w-7 h-7 text-[#5C4A35]')
        <div>
            <h2 class="text-2xl font-bold text-[#3E2F1E]">Pengaturan Sistem</h2>
            <p class="text-[#9e8065] text-sm">Kelola informasi warung</p>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
    @svg('heroicon-o-check-circle', 'w-5 h-5')
    <p>{{ session('success') }}</p>
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] overflow-hidden max-w-3xl">
    <div class="bg-[#f5e6d3] px-6 py-4 border-b border-[#e8d5c1]">
        <h3 class="font-bold text-[#3E2F1E] flex items-center gap-2">
            @svg('heroicon-o-building-storefront', 'w-5 h-5 text-[#5C4A35]') Informasi Warung
        </h3>
    </div>
    
    <div class="p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-[#5C4A35] mb-2">Nama Warung</label>
                    <input type="text" name="warung_name" value="{{ old('warung_name', $setting->warung_name ?? 'Naliko Warung') }}" required
                        class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
                    @error('warung_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-[#5C4A35] mb-2">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="warung_phone" value="{{ old('warung_phone', $setting->warung_phone) }}"
                        placeholder="Contoh: 081234567890"
                        class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
                    @error('warung_phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#5C4A35] mb-2">Alamat Lengkap</label>
                    <textarea name="warung_address" rows="3" 
                        class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">{{ old('warung_address', $setting->warung_address) }}</textarea>
                    @error('warung_address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-[#5C4A35] mb-2">Deskripsi Singkat (Footer)</label>
                    <textarea name="warung_description" rows="2" 
                        class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">{{ old('warung_description', $setting->warung_description) }}</textarea>
                    @error('warung_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-[#5C4A35] text-[#F7E6CC] px-8 py-2.5 rounded-xl font-semibold hover:bg-[#3E2F1E] transition flex items-center gap-2">
                    @svg('heroicon-o-check', 'w-5 h-5') Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
