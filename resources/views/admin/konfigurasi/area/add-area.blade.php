@extends('layouts.admin')

@section('title', 'Tambah Area - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('area') }}" class="text-lg font-semibold flex items-center mb-4">
        ← Kembali
    </a>

    @if (session('area_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('area_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Tambah Area</h1>

    <form action="{{ route('add-area') }}" method="POST" class="max-w-lg mx-auto">
    @csrf
    <div class="mb-4">
        <label for="lantai" class="block text-gray-700 font-semibold mb-2">Lantai</label>
        <div class="relative">
            <select name="lantai" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none" required>
                <option value="" hidden selected disabled>Pilih Lantai</option>
                @foreach ($lantai as $item)
                <option value="{{ $item->id }}">{{ $item->lantai }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                <i class="fas fa-chevron-down text-gray-500"></i>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label for="area" class="block text-gray-700 font-semibold mb-2">Area</label>
        <input type="text" name="area" id="area" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div class="mb-4">
        <label for="pic_area" class="block text-gray-700 font-semibold mb-2">PIC Area</label>
        <div class="relative">
            <select name="pic_area" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none" required>
                <option value="" hidden selected disabled>Pilih PIC Area</option>
                @foreach ($karyawan as $item)
                <option value="{{ $item->emp_id }}">{{ $item->emp_name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                <i class="fas fa-chevron-down text-gray-500"></i>
            </div>
        </div>
    </div>

    <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition duration-200">
        Tambah Area
    </button>
</form>

</div>
@endsection