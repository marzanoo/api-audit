@extends('layouts.auditor')

@section('title', 'Audit Form - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    @if (session('form-audit_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('form-audit_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Isi Form Audit</h1>

    <form action="{{ route('audit-answer-insert') }}" method="POST" class="max-w-lg mx-auto">
    @csrf
    <div class="mb-4">
        <input type="text" name="auditor_id" id="auditor_id" value="{{ auth()->user()->id }}" hidden class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required readonly>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">Nama Auditor</label>
        <input type="text" placeholder="{{ auth()->user()->name }}" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
    </div>
    <div class="mb-4">
        <label for="tanggal" class="block text-gray-700 font-semibold mb-2">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <div class="mb-4">
        <label for="area" class="block text-gray-700 font-semibold mb-2">Area</label>
        <div class="relative">
            <select name="area" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none" required>
                <option value="" hidden selected disabled>Pilih area</option>
                @foreach ($area as $item)
                <option value="{{ $item->id }}">Area {{ $item->area }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                <i class="fas fa-chevron-down text-gray-500"></i>
            </div>
        </div>
    </div>

    <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition duration-200">
        Mulai Audit
    </button>
</form>

</div>
@endsection