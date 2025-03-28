@extends('layouts.admin')
@section('title', 'Konfigurasi Lantai - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('konfigurasi') }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>
    @if (session('lantai_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('lantai_error') }}
    </div>
    @endif

    @if (session('lantai_success'))
    <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
        {{ session('lantai_success') }}
    </div>
    @endif

    <h1 class="text-xl font-bold mb-4">Konfigurasi Lantai</h1>

    <a href="{{ route('add-lantai') }}" class="bg-black text-white px-4 py-2 rounded-lg">+ Tambah Lantai</a>

    <div class="mt-4 space-y-4">
        @foreach ($lantai as $item)
        <div class="bg-gray-100 p-4 rounded-lg flex justify-between items-center shadow-lg">
            <span class="text-lg font-semibold">Lantai {{ $item->lantai }}</span>
            <form action="{{ route('delete-lantai', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Hapus</button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection