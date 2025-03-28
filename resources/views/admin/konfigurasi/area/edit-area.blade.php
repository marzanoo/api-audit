@extends('layouts.admin')

@section('title', 'Tambah Area - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('area') }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    @if (session('area_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('area_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Ubah Area</h1>

    <form action="{{ route('edit-area', $area->id) }}" method="POST" class="max-w-lg mx-auto">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label for="lantai" class="block text-gray-700 font-semibold mb-2">Lantai</label>
        <div class="relative">
            <select name="lantai" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none" required>
                @foreach ($lantai as $item)
                <option value="{{ $item->id }}" {{ $area->lantai_id == $item->id ? 'selected' : '' }}>Lantai {{ $item->lantai }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                <i class="fas fa-chevron-down text-gray-500"></i>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label for="area" class="block text-gray-700 font-semibold mb-2">Area</label>
        <input type="text" name="area" id="area" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $area->area }}" required>
    </div>

    <div class="mb-4">
        <label for="pic_area" class="block text-gray-700 font-semibold mb-2">PIC Area</label>
        <div class="relative">
            <select name="pic_area" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none" required>
                @foreach ($karyawan as $item)
                <option value="{{ $item->emp_id }}" {{ $area->pic_area == $item->emp_id ? 'selected' : '' }}>{{ $item->emp_name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                <i class="fas fa-chevron-down text-gray-500"></i>
            </div>
        </div>
    </div>

    <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition duration-200">
        Ubah Area
    </button>
</form>

</div>
@endsection