@extends('layouts.steerco')
@section('title', 'Audit Office - Audit App')

@section('content')
<div class="container mx-auto p-4">
    <a href="{{ route('audit-office-steerco-area', $lantaiId) }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>
    <h1 class="text-xl font-bold mb-4">Audit Office - Pilih Form</h1>
    <div class="mt-4 space-y-4">
        @foreach ($audit_form as $item)
        <div class="bg-gray-100 p-4 rounded-lg shadow-lg">
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Tanggal</span>
                <span class="text-lg w-2/3">{{ $item->tanggal }}</span>
            </div>
        
            {{-- Data Auditor --}}
            <div class="flex justify-between">
                <span class="text-lg font-semibold w-1/3">Auditor</span>
                <span class="text-lg w-2/3">{{ $item->auditor->name }}</span>
            </div>

            {{-- Lihat--}}
            <div class="flex gap-2 mt-3 justify-end">
                {{-- Tombol Lihat --}}
                <a href="{{ route('detail-audit-office-steerco-audit-form', $item->id) }}" class="bg-red-900 text-white px-4 py-2 rounded-lg text-center">
                    Lihat
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection