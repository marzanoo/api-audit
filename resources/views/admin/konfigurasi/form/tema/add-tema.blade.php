@extends('layouts.admin')

@section('title', 'Tambah Tema - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('tema-form', $formId) }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    @if (session('tema_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('tema_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Tambah Tema</h1>

    <form action="{{ route('add-tema-form', $formId) }}" method="POST" class="max-w-lg mx-auto">
        @csrf
        <div class="mb-4">
            <label for="tema" class="block text-gray-700 font-semibold mb-2">Tema</label>
            <input type="text" name="tema" id="tema" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Masukkan Tema">
        </div>
        <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg">Tambah Tema</button>
    </form>
</div>
@endsection