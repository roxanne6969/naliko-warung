@extends('layouts.app')

@section('title', 'User - Admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">👥 Kelola User</h2>
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-orange-500 text-sm">← Kembali</a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Form Tambah User --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-800 mb-4">Tambah User</h3>
        <form action="{{ route('users.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="name" placeholder="Nama"
                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            <input type="email" name="email" placeholder="Email"
                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            <input type="password" name="password" placeholder="Password"
                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            <select name="role" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
                <option value="kasir">Kasir</option>
                <option value="admin">Admin</option>
            </select>
            <button class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600">
                Tambah User
            </button>
        </form>
    </div>

    {{-- List User --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-800 mb-4">Daftar User</h3>
        <div class="space-y-3">
            @foreach($users as $user)
            <div class="flex items-center justify-between py-2 border-b last:border-0">
                <div>
                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $user->email }}</p>
                    <span class="px-2 py-0.5 rounded-full text-xs
                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                @if($user->id !== auth()->id())
                <form action="{{ route('users.destroy', $user) }}" method="POST">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Hapus user ini?')"
                        class="text-red-400 hover:text-red-600 text-sm">Hapus</button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection