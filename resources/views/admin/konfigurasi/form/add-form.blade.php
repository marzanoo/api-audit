@extends('layouts.admin')

@section('title', 'Tambah Form')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('form') }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    @if (session('form_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('form_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Tambah Form</h1>

    <form action="{{ route('add-form') }}" method="POST" class="max-w-lg mx-auto">
        @csrf
        <div class="mb-4">
            <label for="kategori" class="block text-gray-700 font-semibold mb-2">Kategori</label>
            <input type="text" name="kategori" id="kategori" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="deskripsi" class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
            <input type="text" name="deskripsi" id="deskripsi" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg">Tambah Form</button>
    </form>
</div>
@endsection