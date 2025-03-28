@extends('layouts.admin')

@section('title', 'Tambah Lantai')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('lantai') }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    @if (session('lantai_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('lantai_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Tambah Lantai</h1>

    <form action="{{ route('add-lantai') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="lantai" class="block text-gray-700 font-semibold mb-2">Lantai</label>
            <input type="number" name="lantai" id="lantai" class="w-full p-2 border border-gray-300 rounded-lg" required>
        </div>
        <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg">Tambah Lantai</button>
    </form>
</div>
@endsection