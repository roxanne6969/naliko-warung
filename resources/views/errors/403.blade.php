@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')

<div class="min-h-96 flex items-center justify-center">
    <div class="text-center">
        <div class="text-8xl mb-4">🚫</div>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">403</h1>
        <p class="text-gray-400 mb-6">Akses ditolak. Kamu tidak punya izin untuk halaman ini.</p>
        <a href="{{ url()->previous() }}" 
            class="bg-orange-500 text-white px-6 py-3 rounded-xl hover:bg-orange-600">
            ← Kembali
        </a>
    </div>
</div>

@endsection
