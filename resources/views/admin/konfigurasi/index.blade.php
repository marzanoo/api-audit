@extends('layouts.admin')
@section('title', 'Konfigurasi')

@section('content')
<div class="container mx-auto p-4 space-y-6">
    <div>
        <h2 class="text-lg font-bold mb-2">Konfigurasi Objek Audit</h2>
        <a href="{{ route('lantai') }}" class="block bg-gray-100 p-4 rounded-lg shadow-md flex justify-between items-center hover:bg-gray-200 transition">
            <div>
                <h3 class="text-lg font-semibold">Konfigurasi Lantai</h3>
                <p class="text-gray-600">Total Lantai : {{ $total_lantai }}</p>
            </div>
            <span class="text-xl">➤</span>
        </a>
        <a href="{{ route('area') }}" class="block bg-gray-100 p-4 rounded-lg shadow-md flex justify-between items-center mt-2 hover:bg-gray-200 transition">
            <div>
                <h3 class="text-lg font-semibold">Konfigurasi Area</h3>
                <p class="text-gray-600">Total Area : {{ $total_area }}</p>
            </div>
            <span class="text-xl">➤</span>
        </a>
    </div>
    
    <div>
        <h2 class="text-lg font-bold mb-2">Konfigurasi Form Audit</h2>
        <a href="#" class="block bg-gray-100 p-4 rounded-lg shadow-md flex justify-between items-center hover:bg-gray-200 transition">
            <div>
                <h3 class="text-lg font-semibold">Konfigurasi Form</h3>
                <p class="text-gray-600">Total Variabel : 21</p>
            </div>
            <span class="text-xl">➤</span>
        </a>
    </div>
    
    <div>
        <h2 class="text-lg font-bold mb-2">Konfigurasi Pengguna</h2>
        <a href="#" class="block bg-gray-100 p-4 rounded-lg shadow-md flex justify-between items-center hover:bg-gray-200 transition">
            <div>
                <h3 class="text-lg font-semibold">Konfigurasi Pengguna</h3>
                <p class="text-gray-600">Total Pengguna : 17</p>
            </div>
            <span class="text-xl">➤</span>
        </a>
    </div>
</div>
@endsection