@extends('layouts.steerco')
@section('title', 'Home - Audit App')
    
@section('content')
<div class="container mx-auto p-4">
    @if (session('audit_office_error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
            {{ session('audit_office_error') }}
        </div>
    @endif

    @if (session('audit_office_success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('audit_office_success') }}
        </div>
    @endif

    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold">Selamat Datang!</h1>
        <p class="text-xl mb-2 mt-2">{{ auth()->user()->name }}</p>
        <p class="text-lg font-semibold">Role : 
            @if (auth()->user()->role == 2) 
                <span class="bg-red-500 text-white px-2 py-1 rounded">Steering Committee</span>
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
        <a href="{{ route('audit-office-steerco') }}" class="bg-gray-100 p-4 rounded-lg shadow-md text-center">
            <h4 class="text-xl font-bold">Isi Form Audit</h4>
            <p class="text-2xl text-green-600 mt-3 font-semibold">Lihat</p>
        </a>
    </div>
</div>
@endsection