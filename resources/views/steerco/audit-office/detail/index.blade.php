@extends('layouts.steerco')
@section('title', 'Audit Office - Audit App')

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
    <a href="{{ route('audit-office-steerco-audit-form', $auditAnswer->area_id) }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Audit Office</h2>
        </div>
        <div class="p-6 space-y-8">
            @foreach ($formattedData as $detail)
                <div class="audit-detail-section bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <div class="mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            {{ $detail['kategori'] }}
                        </h3>
                        <h4 class="text-lg font-medium text-gray-600 mb-2">
                            {{ $detail['tema'] }}
                        </h4>
                        <p class="text-gray-600 mb-2">
                            <strong>Standar:</strong> {{ $detail['standar_variabel'] }}
                        </p>
                        @if ($detail['standar_foto'])
                            <div class="mt-3 mb-4">
                                <h5 class="text-md font-medium text-gray-700 mb-2">Foto Standar:</h5>
                                <img src="{{ asset('storage/' . $detail['standar_foto']) }}" alt="Foto Standar" class="w-40 h-40 object-cover rounded-lg shadow-sm">
                            </div>
                        @endif
                        <p class="text-gray-600 mb-2">
                            {{ $detail['variabel'] }}
                        </p>
                    </div>
                    
                    <!-- Temuan Foto -->
                    @if (count($detail['images']) > 0)
                        <div class="mt-6 mb-4">
                            <h5 class="text-md font-medium text-gray-700 mb-2">Foto Temuan:</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($detail['images'] as $image)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $image['image_path']) }}" alt="Foto Temuan" class="w-full h-32 object-cover rounded-lg shadow-sm">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Tertuduh & Temuan -->
                    @if (count($detail['auditees']) > 0)
                        <div class="mt-6 mb-4">
                            <h5 class="text-md font-medium text-gray-700 mb-2">Data Tertuduh & Temuan:</h5>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <ul class="space-y-2">
                                    @foreach ($detail['auditees'] as $auditee)
                                        <li class="flex justify-between items-center p-2 bg-white rounded-lg shadow-sm">
                                            <span>{{ $auditee['auditee'] }}</span>
                                            <span class="font-medium">Temuan: {{ $auditee['temuan'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Score -->
                    <div class="mt-6">
                        <h5 class="text-md font-medium text-gray-700 mb-2">Score:</h5>
                        <div class="bg-gray-100 p-3 rounded-lg inline-block">
                            <span class="font-bold text-lg">{{ $detail['score'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-6">
                <div class="flex items-center">
                    <h5 class="text-md font-medium text-gray-700">Total Score:</h5>
                    <span class="font-bold text-md mx-2">{{ $auditAnswer->total_score }}</span>
                </div>
            
                <div class="flex items-center mt-2">
                    <h5 class="text-md font-medium text-gray-700">Grade:</h5>
                    <span class="font-bold text-md mx-2">{{ $grade }}</span>
                </div>
            </div>            
            <!-- Signatures Section -->
            @if (isset($signatures))
                <div class="bg-gray-50 rounded-xl p-6 shadow-md border border-gray-100 mt-8">
                    <h3 class="text-xl font-semibold mb-6 text-center">Tanda Tangan</h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <h4 class="font-medium mb-3">Tanda Tangan Auditor</h4>
                            @if ($signatures->auditor_signature)
                                <img src="{{ asset('storage/' . $signatures->auditor_signature) }}" alt="Tanda Tangan Auditor" class="border-2 border-gray-300 rounded-lg mx-auto" width="300">
                            @else
                                <div class="border-2 border-gray-300 rounded-lg mx-auto h-36 flex items-center justify-center text-gray-500">
                                    Tidak ada tanda tangan
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <h4 class="font-medium mb-3">Tanda Tangan Auditee</h4>
                            @if ($signatures->auditee_signature)
                                <img src="{{ asset('storage/' . $signatures->auditee_signature) }}" alt="Tanda Tangan Auditee" class="border-2 border-gray-300 rounded-lg mx-auto" width="300">
                            @else
                                <div class="border-2 border-gray-300 rounded-lg mx-auto h-36 flex items-center justify-center text-gray-500">
                                    Tidak ada tanda tangan
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <h4 class="font-medium mb-3">Tanda Tangan Fasilitator</h4>
                            @if ($signatures->facilitator_signature)
                                <img src="{{ asset('storage/' . $signatures->facilitator_signature) }}" alt="Tanda Tangan Fasilitator" class="border-2 border-gray-300 rounded-lg mx-auto" width="300">
                            @else
                                <div class="border-2 border-gray-300 rounded-lg mx-auto h-36 flex items-center justify-center text-gray-500">
                                    Tidak ada tanda tangan
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection