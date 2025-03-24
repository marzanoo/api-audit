@extends('layouts.admin')
@section('title', 'Konfigurasi Area - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    {{-- Tombol Kembali --}}
    <a href="{{ route('konfigurasi') }}" class="text-lg font-semibold flex items-center mb-4">
        ← Kembali
    </a>

    {{-- Notifikasi --}}
    @if (session('area_error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
            {{ session('area_error') }}
        </div>
    @endif

    @if (session('area_success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('area_success') }}
        </div>
    @endif

    {{-- Judul Halaman --}}
    <h1 class="text-xl font-bold mb-4">Konfigurasi Lantai</h1>

    {{-- Tombol Tambah Area --}}
    <a href="{{ route('add-area') }}" class="bg-black text-white px-4 py-2 rounded-lg">+ Tambah Area</a>

    {{-- List Area --}}
    <div class="mt-4 space-y-4">
        @foreach ($area as $item)
        <div class="bg-gray-100 p-4 rounded-lg shadow-lg">
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Lantai</span>
                <span class="text-lg w-2/3">{{ $item->lantai->lantai }}</span>
            </div>
        
            {{-- Data Area --}}
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Area</span>
                <span class="text-lg w-2/3">{{ $item->area }}</span>
            </div>
        
            {{-- Data PIC Area --}}
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">PIC Area</span>
                <span class="text-lg w-2/3">{{ $item->karyawans->emp_name }}</span>
            </div>

            {{-- Tombol Edit & Hapus --}}
            <div class="flex gap-2 mt-3">
                {{-- Tombol Edit --}}
                <a href="{{ route('edit-area', $item->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-center">
                    Edit
                </a>

                {{-- Tombol Hapus --}}
                <form action="{{ route('delete-area', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg w-full">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
