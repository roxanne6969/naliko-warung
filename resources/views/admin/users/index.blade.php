@extends('layouts.app')
@section('title', 'User - Admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @svg('heroicon-o-user-group', 'w-6 h-6 text-[#5C4A35]')
        <h2 class="text-2xl font-bold text-[#3E2F1E]">Kelola User</h2>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 text-[#9e8065] hover:text-[#5C4A35] text-sm transition">
        @svg('heroicon-o-arrow-left', 'w-4 h-4') Kembali
    </a>
</div>
@if(session('success'))
    <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
        @svg('heroicon-o-check-circle', 'w-5 h-5') {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4">
        @svg('heroicon-o-x-circle', 'w-5 h-5') {{ session('error') }}
    </div>
@endif
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6">
        <h3 class="font-bold text-[#3E2F1E] mb-4">Tambah User</h3>
        <form action="{{ route('users.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="name" placeholder="Nama"
                class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            <input type="email" name="email" placeholder="Email"
                class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            <input type="password" name="password" placeholder="Password"
                class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
            <select name="role" class="w-full border border-[#e8d5c1] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#5C4A35] bg-[#fdfaf7] text-[#3E2F1E]">
                <option value="kasir">Kasir</option>
                <option value="admin">Admin</option>
            </select>
            <button class="w-full flex items-center justify-center gap-2 bg-[#5C4A35] text-[#F7E6CC] py-2.5 rounded-xl hover:bg-[#3E2F1E] transition font-semibold">
                @svg('heroicon-o-user-plus', 'w-4 h-4') Tambah User
            </button>
        </form>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-[#e8d5c1] p-6">
        <h3 class="font-bold text-[#3E2F1E] mb-4">Daftar User</h3>
        <div class="space-y-3">
            @foreach($users as $user)
            <div class="flex items-center justify-between py-2.5 border-b border-[#f0e0cc] last:border-0">
                <div>
                    <p class="font-semibold text-[#3E2F1E]">{{ $user->name }}</p>
                    <p class="text-[#9e8065] text-xs">{{ $user->email }}</p>
                    <span class="mt-1 inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                        {{ $user->role === 'admin' ? 'bg-[#f0e0cc] text-[#5C4A35]' : 'bg-blue-50 text-blue-600' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                @if($user->id !== auth()->id())
                <form action="{{ route('users.destroy', $user) }}" method="POST">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Hapus user ini?')"
                        class="flex items-center gap-1 text-xs text-red-400 hover:text-red-600 transition">
                        @svg('heroicon-o-trash', 'w-3.5 h-3.5') Hapus
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection