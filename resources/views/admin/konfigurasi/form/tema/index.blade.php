@extends('layouts.admin')
@section('title', 'Konfigurasi Tema - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    {{-- Tombol Kembali --}}
    <a href="{{ route('form') }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    {{-- Notifikasi --}}
    @if (session('tema_error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
            {{ session('tema_error') }}
        </div>
    @endif

    @if (session('tema_success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('tema_success') }}
        </div>
    @endif

    {{-- Judul Halaman --}}
    <h1 class="text-xl font-bold mb-4">Konfigurasi Tema</h1>

    {{-- Tombol Tambah Tema --}}
    <a href="{{ route('add-tema-form', $formId) }}" class="bg-black text-white px-4 py-2 rounded-lg">+ Tambah Tema</a>

    {{-- List Tema --}}
    <div class="mt-4 space-y-4">
        @foreach ($tema as $item)
        <div class="bg-gray-100 p-4 rounded-lg shadow-lg">
            {{-- Data Tema --}}
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Tema</span>
                <span class="text-lg w-2/3">{{ $item->tema }}</span>
            </div>

            {{-- Tombol View & Hapus --}}
            <div class="flex gap-2 mt-3">
                {{-- Tombol View --}}
                <a href="{{ route('variabel-form', $item->id) }}" class="bg-cyan-500 text-white px-4 py-2 rounded-lg text-center">
                    Detail
                </a>
                <a href="{{ route('edit-tema-form', $item->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-center">
                    Ubah
                </a>

                {{-- Tombol Hapus --}}
                <form action="{{ route('delete-tema-form', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
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
