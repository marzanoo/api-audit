@extends('layouts.admin')

@section('title', 'Ubah Tema - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('tema-form', $tema->form_id) }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    @if (session('tema_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('tema_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Ubah Tema</h1>

    <form action="{{ route('edit-tema-form', $tema->id) }}" method="POST" class="max-w-lg mx-auto">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label for="tema" class="block text-gray-700 font-semibold mb-2">Tema</label>
        <input type="text" name="tema" id="tema" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $tema->tema }}" required>
    </div>
    <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition duration-200">
        Ubah Tema
    </button>
</form>

</div>
@endsection