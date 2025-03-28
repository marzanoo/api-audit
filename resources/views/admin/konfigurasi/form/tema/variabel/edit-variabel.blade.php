@extends('layouts.admin')

@section('title', 'Ubah Variabel - Audit App')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <a href="{{ route('variabel-form', $variabel->tema_form_id) }}" class="text-lg font-semibold flex items-center mb-4 w-fit">
        ← Kembali
    </a>

    @if (session('variabel_error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        {{ session('variabel_error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Ubah Variabel</h1>

    <form action="{{ route('edit-variabel-form', $variabel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="variabel" class="block text-gray-700 font-semibold mb-2">Variabel</label>
            <input type="text" name="variabel" id="variabel" value="{{ $variabel->variabel }}" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Masukkan variabel">
        </div>  
        <div class="mb-4">
            <label for="standar_variabel" class="block text-gray-700 font-semibold mb-2">Standar</label>
            <input type="text" name="standar_variabel" id="standar_variabel" value="{{ $variabel->standar_variabel }}" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Masukkan standar">
        </div>  
        <div class="mb-4">
            <label for="standar_foto" class="block text-gray-700 font-semibold mb-2">Foto</label>
            <input type="file" accept="image/*" name="standar_foto" id="standar_foto" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="previewImage(event)">
            
            <div id="image-preview-container" class="mt-4 {{ $variabel->standar_foto ? '' : 'hidden' }}">
                <div class="relative w-[120px]">
                    <img id="image-preview" 
                         src="{{ $variabel->standar_foto ? asset('storage/' . $variabel->standar_foto) : '#' }}" 
                         alt="Image Preview" 
                         class="w-[120px] h-[120px] object-cover rounded-lg shadow-md">
                </div>
            </div>
        </div>  
        <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg">Simpan Perubahan</button>
    </form>
</div>
@push('scripts')   
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');
        const previewContainer = document.getElementById('image-preview-container');
        const removeButton = document.getElementById('remove-image');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {  
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                removeButton.classList.remove('hidden'); // Tampilkan tombol hapus hanya jika ada foto baru
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            previewContainer.classList.add('hidden');
            removeButton.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection