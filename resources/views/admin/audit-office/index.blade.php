@extends('layouts.admin')
@section('title', 'Audit Office - Audit App')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Audit Office - Pilih Lantai</h1>
    <div class="mt-4 space-y-4">
        @foreach ($lantai as $item)
        <div class="bg-gray-100 p-4 rounded-lg flex justify-between items-center shadow-lg">
            <span class="text-lg font-semibold">Lantai {{ $item->lantai }}</span>
            <a href="{{ route('audit-office-admin-area', $item->id) }}" class="bg-red-900 text-white px-4 py-2 rounded-lg">Lihat</a>
        </div>
        @endforeach
    </div>
</div>
@endsection