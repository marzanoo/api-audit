@extends('layouts.admin')
@section('title', 'Preview Excel - Audit App')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('detail-audit-office-admin-audit-form', $id) }}" class="text-lg font-semibold flex items-center w-fit">
            ← Kembali
        </a>
        <div class="flex space-x-3">
            <a href="{{ route('audit-office-admin-download-excel', $id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Excel
            </a>
            {{-- <a href="{{ route('audit-office-admin-download-pdf', $id) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download PDF
            </a> --}}
        </div>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Preview Ekspor Excel</h2>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tema</th>
                            <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Standar</th>
                            <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto Standar</th>
                            <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Variabel</th>
                            <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Score</th>
                            <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Temuan</th>
                            <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto Temuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($formattedData as $detail)
                            <tr>
                                <td class="px-4 py-2 border-b border-gray-200 text-sm">{{ $detail['kategori'] }}</td>
                                <td class="px-4 py-2 border-b border-gray-200 text-sm">{{ $detail['tema'] }}</td>
                                <td class="px-4 py-2 border-b border-gray-200 text-sm">{{ $detail['standar_variabel'] }}</td>
                                <td class="px-4 py-2 border-b border-gray-200 text-sm">
                                    @if ($detail['standar_foto'])
                                        <img src="{{ asset('storage/' . $detail['standar_foto']) }}" alt="Foto Standar" class="w-20 h-20 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 text-sm">{{ $detail['variabel'] }}</td>
                                <td class="px-4 py-2 border-b border-gray-200 text-sm">{{ $detail['score'] }}</td>
                                <td class="px-4 py-2 border-b border-gray-200 text-sm">
                                    @foreach ($detail['auditees'] as $auditee)
                                        <div class="mb-1">
                                            <strong>{{ $auditee['auditee'] }}:</strong> {{ $auditee['temuan'] }}
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 text-sm">
                                    @if (count($detail['images']) > 0)
                                        <div class="flex flex-col space-y-3">
                                            @foreach ($detail['images'] as $image)
                                                <div class="w-full">
                                                    <img src="{{ asset('storage/' . $image['image_path']) }}" alt="Foto Temuan" class="w-full max-w-xs object-cover rounded">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">Tidak ada foto</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50">
                            <td class="px-4 py-2 border-b border-gray-200 text-sm" colspan="4"></td>
                            <td class="px-4 py-2 border-b border-gray-200 text-sm font-bold">Total Score</td>
                            <td class="px-4 py-2 border-b border-gray-200 text-sm font-bold">{{ $auditAnswer->total_score }}</td>
                            <td class="px-4 py-2 border-b border-gray-200 text-sm" colspan="2"></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-2 border-b border-gray-200 text-sm" colspan="4"></td>
                            <td class="px-4 py-2 border-b border-gray-200 text-sm font-bold">Grade</td>
                            <td class="px-4 py-2 border-b border-gray-200 text-sm font-bold">{{ $grade }}</td>
                            <td class="px-4 py-2 border-b border-gray-200 text-sm" colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection