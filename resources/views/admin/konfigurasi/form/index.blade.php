@extends('layouts.admin')
@section('title', 'Konfigurasi Form - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    {{-- Tombol Kembali --}}
    <a href="{{ route('konfigurasi') }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    {{-- Notifikasi --}}
    @if (session('form_error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
            {{ session('form_error') }}
        </div>
    @endif

    @if (session('form_success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('form_success') }}
        </div>
    @endif

    {{-- Judul Halaman --}}
    <h1 class="text-xl font-bold mb-4">Konfigurasi Form</h1>

    {{-- Tombol Tambah Form --}}
    <a href="{{ route('add-form') }}" class="bg-black text-white px-4 py-2 rounded-lg">+ Tambah Form</a>

    {{-- List Form --}}
    <div class="mt-4 space-y-4">
        @foreach ($forms as $item)
        <div class="bg-gray-100 p-4 rounded-lg shadow-lg">
            {{-- Data Form --}}
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Kategori</span>
                <span class="text-lg w-2/3">{{ $item->kategori }}</span>
            </div>
        
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Deskripsi</span>
                <div class="text-lg w-2/3 text-justify whitespace-pre-line">{{ $item->deskripsi }}</div>
            </div>

            {{-- Tombol View & Hapus --}}
            <div class="flex gap-2 mt-3">
                {{-- Tombol View --}}
                <a href="{{ route('tema-form', $item->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-center">
                    View
                </a>

                {{-- Tombol Hapus --}}
                <form action="{{ route('delete-form', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
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
