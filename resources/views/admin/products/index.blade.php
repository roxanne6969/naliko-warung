@extends('layouts.app')

@section('title', 'Produk - Admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">🛍️ Kelola Produk</h2>
    <a href="{{ route('products.create') }}" 
        class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-[#766349]">
        + Tambah Produk
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
@endif

<div class="bg-[#F5E2C7] rounded-xl shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Produk</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Kategori</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Harga</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Stok</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Status</th>
                <th class="px-4 py-3 text-left text-sm text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($products as $product)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                    <p class="text-gray-400 text-xs">{{ Str::limit($product->description, 40) }}</p>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $product->category->name }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-orange-500">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $product->stock }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs
                        {{ $product->is_available ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('products.edit', $product) }}" 
                        class="text-blue-500 hover:text-blue-700 text-sm">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Hapus produk ini?')"
                            class="text-red-400 hover:text-red-600 text-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection