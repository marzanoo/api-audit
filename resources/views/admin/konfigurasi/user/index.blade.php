@extends('layouts.admin')
@section('title', 'Konfigurasi User - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    {{-- Tombol Kembali --}}
    <a href="{{ route('konfigurasi') }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    {{-- Notifikasi --}}
    @if (session('area_error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
            {{ session('user_error') }}
        </div>
    @endif

    @if (session('user_success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('user_success') }}
        </div>
    @endif

    {{-- Judul Halaman --}}
    <h1 class="text-xl font-bold mb-4">Konfigurasi User</h1>

    {{-- Tombol Tambah Area --}}
    <a href="{{ route('add-user') }}" class="bg-black text-white px-4 py-2 rounded-lg">+ Tambah User</a>

    {{-- List Area --}}
    <div class="mt-4 space-y-4">
        @foreach ($users as $item)
        <div class="bg-gray-100 p-4 rounded-lg shadow-lg">
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Nama</span>
                <span class="text-lg w-2/3">{{ $item->name }}</span>
            </div>
        
            {{-- Data Area --}}
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Role</span>
                <span class="text-lg w-2/3">
                    @php
                        $roleText = match($item->role) {
                            1 => 'Admin',
                            2 => 'Steering Committee',
                            3 => 'Auditor',
                            default => 'Undefined',
                        };
                    @endphp
                    {{ $roleText }}
                </span>                
            </div>
        
            {{-- Data PIC Area --}}
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Email</span>
                <span class="text-lg w-2/3">{{ $item->email }}</span>
            </div>

            {{-- Tombol Edit & Hapus --}}
            <div class="flex gap-2 mt-3">
                {{-- Tombol Edit --}}
                <a href="{{ route('edit-user', $item->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-center">
                    Edit
                </a>

                {{-- Tombol Hapus --}}
                <form action="{{ route('delete-user', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg w-full">
                        Hapus
                    </button>
                </form>

                <form action="{{ route('reset-device', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="bg-red-900 text-white px-4 py-2 rounded-lg w-full">
                        Reset Device
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection