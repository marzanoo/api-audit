@extends('layouts.admin')
@section('title', 'Admin Home')
    
@section('content')
<div class="container mx-auto p-4">
    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold">Selamat Datang!</h1>
        <p class="text-xl mb-2 mt-2">{{ auth()->user()->name }}</p>
        <p class="text-lg font-semibold">Role : 
            @if (auth()->user()->role == 1) 
                <span class="bg-green-500 text-white px-2 py-1 rounded">Admin</span>
            @endif
        </p>
    </div>
</div>

<div class="container mx-auto p-4">
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-100 p-4 rounded-lg shadow-md text-center">
            <h4 class="text-xl font-bold">Total Audit Selesai</h4>
            <p class="text-4xl text-green-600 mt-3 font-semibold">{{ $total_audit }}</p>
        </div>
        <div class="bg-gray-100 p-6 rounded-lg shadow-md text-center">
            <h4 class="text-xl font-bold">Audit Office</h4>
            <a href="{{ route('audit-office-admin') }}" class="bg-black hover:bg-red-900 text-white px-4 py-2 rounded mt-3 inline-block">Lihat</a>
        </div>
    </div>
</div>

<div class="container mx-auto p-4">
    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
        <h1 class="text-xl font-bold mb-2">Konfigurasi</h1>
        <p class="mb-1">> Konfigurasi Objek Audit</p>
        <p class="mb-1">> Konfigurasi Form</p>
        <p class="mb-1">> Konfigurasi Pengguna</p>
    </div>
</div>
@endsection