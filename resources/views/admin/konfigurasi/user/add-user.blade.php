@extends('layouts.admin')
@section('title', 'Konfigurasi User - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('users') }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    @if (session('user_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('user_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Tambah User</h1>

    <form action="{{ route('add-user') }}" method="POST" class="max-w-lg mx-auto">
        @csrf
        <div class="mb-4">
            <label for="nik" class="block text-gray-700 font-semibold mb-2">NIK</label>
            <input type="number" name="nik" id="nik" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Nama</label>
            <input type="text" name="name" id="name" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
            <input type="text" name="username" id="username" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" id="email" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
            <input type="password" name="password" id="password" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="pic_area" class="block text-gray-700 font-semibold mb-2">Role</label>
            <div class="relative">
                <select name="role" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none" required>
                    <option value="1">Admin</option>
                    <option value="2">Steering Committee</option>
                    <option value="3">Auditor</option>
                </select>
            </div>
        </div>
        <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition duration-200">
            Tambah User
        </button>
    </form>
</div>
@endsection