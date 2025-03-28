@extends('layouts.admin')

@section('title', 'Tambah Variabel - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('variabel-form', $temaFormId) }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    @if (session('variabel_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('variabel_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Tambah variabel</h1>

    <form action="{{ route('add-variabel-form', $temaFormId) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="variabel" class="block text-gray-700 font-semibold mb-2">Variabel</label>
            <input type="text" name="variabel" id="variabel" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Masukkan variabel">
        </div>  
        <div class="mb-4">
            <label for="standar_variabel" class="block text-gray-700 font-semibold mb-2">Standar</label>
            <input type="text" name="standar_variabel" id="standar_variabel" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Masukkan standar">
        </div>  
        <div class="mb-4">
            <label for="standar_foto" class="block text-gray-700 font-semibold mb-2">Foto</label>
            <input type="file" accept="image/*" name="standar_foto" id="standar_foto" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="previewImage(event)">
            <div id="image-preview-container" class="mt-4 hidden">
                <div class="relative w-[150px]">
                    <img id="image-preview" src="#" alt="Image Preview" class="w-[120px] h-[120px] object-cover rounded-lg shadow-md">
                </div>
            </div>
        </div>  
        <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg">Tambah Variabel</button>
    </form>
</div>
@push('scripts')
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');
        const previewContainer = document.getElementById('image-preview-container');    

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {  
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            previewContainer.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection