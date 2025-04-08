@extends('layouts.auditor')
@section('title', 'Isi Form Audit - Audit App')

@section('content')
<div class="container mx-auto p-4">
    @if (session('audit_answer_error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
            {{ session('audit_answer_error') }}
        </div>
    @endif
    @if (session('form_audit_success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('form_audit_success') }}
        </div>
    @endif
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Isi Form Audit</h2>
        </div>
        <div class="space-y-4">
            <form action="{{ route('detail-audit-answer-insert', $auditAnswerId) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="auditForm">
                @csrf
                <input type="hidden" name="audit_answer_id" value="{{ $auditAnswerId }}">
                @foreach ($detailAuditAnswer as $detail)
                    <div class="audit-detail-section mb-8 p-4" data-detail-id="{{ $detail['id'] }}">
                        <input type="hidden" name="id" value="{{ $detail['id'] }}">
                        <input type="hidden" name="detail_audit_answer_id" value="{{ $detail['id'] }}">                    
                        <div class="mb-2">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                {{ $detail['kategori'] }}
                            </h3>
                            <h4 class="text-lg font-medium text-gray-600 mb-2">
                                {{ $detail['tema'] }}
                            </h4>
                            <p class="text-gray-600 mb-2">
                                <strong>Standar:</strong> {{ $detail['standar_variabel'] }}
                            </p>
                            <div class="text-lg w-2/3">
                                @if ($detail['standar_foto'])
                                    <img src="{{ asset('storage/' . $detail['standar_foto']) }}" loading="eager" alt="Foto Variabel" class="w-32 h-32 object-cover">
                                @else
                                    Tidak ada foto
                                @endif
                            </div>
                            <p class="text-gray-600 mb-2">
                                {{ $detail['variabel'] }}
                            </p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-md font-medium text-gray-700 mb-2">
                                Foto Temuan
                            </label>
                            <div class="flex items-center mb-4">
                                <input 
                                    type="file" 
                                    id="fileInput-{{ $detail['id'] }}"
                                    accept="image/*" 
                                    multiple 
                                    capture="environment" 
                                    class="hidden" 
                                    onchange="handleFileSelect(event, {{ $detail['id'] }})"
                                >
                                <button 
                                    type="button" 
                                    onclick="document.getElementById('fileInput-{{ $detail['id'] }}').click()" 
                                    class="px-4 py-2 bg-cyan-500 text-white rounded hover:bg-blue-600"
                                >
                                    Ambil Foto
                                </button>
                            </div>
                            
                            <div 
                                id="image-preview-container-{{ $detail['id'] }}" 
                                class="grid grid-cols-3 gap-4"
                            ></div>
                        </div>
                        <div class="mb-4">
                            <label class="text-md font-semibold">Data Tertuduh & Temuan</label>
                            <div class="flex flex-wrap items-end gap-2">
                                <input type="text" id="tertuduh-{{ $detail['id'] }}" placeholder="Tertuduh" class="w-full sm:w-auto px-2 py-1 border border-gray-300 rounded-lg shadow-sm">
                                <input type="number" id="temuan-{{ $detail['id'] }}" placeholder="Temuan" class="w-full sm:w-auto px-2 py-1 border border-gray-300 rounded-lg shadow-sm">
                                <button type="button" onclick="tambahTertuduh('{{ $detail['id'] }}')" class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-700">Tambah</button>
                            </div>
                        </div>
                        
                        <!-- Tempat untuk Menampilkan Data yang Ditambahkan -->
                        <div id="data-list-{{ $detail['id'] }}" class="mt-4 space-y-2"></div>
                                     
                        <div class="mb-2 mt-4">
                            <label for="text-lg font-semibold w-1/3">Score</label>
                            <input type="number" name="score[{{ $detail['id'] }}]" id="inputScore-{{ $detail['id'] }}" value="0" readonly class="score-input px-2 font-medium text-md border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>                        
                    </div>
                @endforeach
                {{-- Signature Section --}}
                <div class="bg-gray-50 rounded-xl p-6 shadow-md border border-gray-100">
                    <h3 class="text-xl font-semibold mb-6 text-center">Tanda Tangan</h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <label class="block mb-4 font-medium">Tanda Tangan Auditor</label>
                            <canvas id="auditorSignatureCanvas" 
                                    class="border-2 border-gray-300 rounded-lg mx-auto" 
                                    width="300" height="150"></canvas>
                            <button type="button" id="clearAuditorSignature" 
                                    class="mt-3 px-4 py-2 bg-red-500 text-white rounded-lg">
                                Hapus
                            </button>
                        </div>
                        <div class="text-center">
                            <label class="block mb-4 font-medium">Tanda Tangan Auditee</label>
                            <canvas id="auditeeSignatureCanvas" 
                                    class="border-2 border-gray-300 rounded-lg mx-auto" 
                                    width="300" height="150"></canvas>
                            <button type="button" id="clearAuditeeSignature" 
                                    class="mt-3 px-4 py-2 bg-red-500 text-white rounded-lg">
                                Hapus
                            </button>
                        </div>
                        <div class="text-center">
                            <label class="block mb-4 font-medium">Tanda Tangan Fasilitator</label>
                            <canvas id="facilitatorSignatureCanvas" 
                                    class="border-2 border-gray-300 rounded-lg mx-auto" 
                                    width="300" height="150"></canvas>
                            <button type="button" id="clearFacilitatorSignature" 
                                    class="mt-3 px-4 py-2 bg-red-500 text-white rounded-lg">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
                <input type="file" name="auditor_signature" id="auditorSignatureInput" accept="image/*" style="display:none;">
                <input type="file" name="auditee_signature" id="auditeeSignatureInput" accept="image/*" style="display:none;">
                <input type="file" name="facilitator_signature" id="facilitatorSignatureInput" accept="image/*" style="display:none;">

                {{-- Submit Button --}}
                <div class="text-center">
                    <button type="submit" 
                            disabled
                            class="w-full max-w-md mx-auto py-3 px-6 bg-indigo-600 text-white rounded-lg 
                                   hover:bg-indigo-700 transition duration-300 
                                   disabled:opacity-50 disabled:cursor-not-allowed mb-4">
                        Simpan Seluruh Detail Audit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const signatureContainers = {
            'auditor': document.getElementById('auditorSignatureCanvas'),
            'auditee': document.getElementById('auditeeSignatureCanvas'),
            'facilitator': document.getElementById('facilitatorSignatureCanvas')
        };

        const clearButtons = {
            'auditor': document.getElementById('clearAuditorSignature'),
            'auditee': document.getElementById('clearAuditeeSignature'),
            'facilitator': document.getElementById('clearFacilitatorSignature')
        };

        const submitButton = document.querySelector('button[type="submit"]');

        // Signature modal HTML
        const signatureModalHTML = `
            <div id="signatureModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 w-11/12 max-w-md">
                    <h3 class="text-xl font-semibold mb-4">Tanda Tangan</h3>
                    <canvas id="modalSignatureCanvas" class="border w-full" width="400" height="200"></canvas>
                    <div class="flex justify-between mt-4">
                        <button id="clearModalSignature" class="bg-gray-300 text-black px-4 py-2 rounded">Hapus</button>
                        <div>
                            <button id="cancelModalSignature" class="bg-red-500 text-white px-4 py-2 rounded mr-2">Batal</button>
                            <button id="saveModalSignature" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        let currentSignatureType = null;
        let signaturePad = null;

        function initSignaturePad(canvas) {
            // Use signature_pad library for better signature capturing
            return new SignaturePad(canvas, {
                minWidth: 1,
                maxWidth: 3,
                penColor: "black"
            });
        }

        function openSignatureModal(type) {
            document.body.insertAdjacentHTML('beforeend', signatureModalHTML);
            const modalCanvas = document.getElementById('modalSignatureCanvas');
            signaturePad = initSignaturePad(modalCanvas);
            currentSignatureType = type;

            document.getElementById('clearModalSignature').addEventListener('click', () => {
                signaturePad.clear();
            });

            document.getElementById('cancelModalSignature').addEventListener('click', closeSignatureModal);

            document.getElementById('saveModalSignature').addEventListener('click', saveSignature);
        }

        function closeSignatureModal() {
            document.getElementById('signatureModal').remove();
            signaturePad = null;
            currentSignatureType = null;
        }

        function saveSignature() {
            if (!signaturePad.isEmpty()) {
                const signatureImage = signaturePad.toDataURL('image/png');
                
                fetch(signatureImage)
                    .then(res => res.blob())
                    .then(blob => {
                        const file = new File([blob], `${currentSignatureType}_signature.png`, { type: 'image/png' });
                        
                        // Pilih input file sesuai tipe signature
                        const fileInput = document.getElementById(`${currentSignatureType}SignatureInput`);
                        
                        // Buat DataTransfer untuk set file
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                        
                        // Lanjutkan proses sebelumnya...
                        const targetCanvas = signatureContainers[currentSignatureType];
                        const ctx = targetCanvas.getContext('2d');
                        
                        ctx.clearRect(0, 0, targetCanvas.width, targetCanvas.height);
                        const img = new Image();
                        img.onload = function() {
                            ctx.drawImage(img, 0, 0, targetCanvas.width, targetCanvas.height);
                            targetCanvas.setAttribute('data-signed', 'true');
                            checkAllSignatures();
                        };
                        img.src = signatureImage;
                        
                        closeSignatureModal();
                    });
            }
        }

        function checkAllSignatures() {
            const allSigned = Object.values(signatureContainers).every(
                canvas => canvas.getAttribute('data-signed') === 'true'
            );
            submitButton.disabled = !allSigned;
        }

        // Initial disable of submit button
        submitButton.disabled = true;

        // Add click events to signature containers and clear buttons
        Object.keys(signatureContainers).forEach(type => {
            signatureContainers[type].addEventListener('click', () => openSignatureModal(type));
            clearButtons[type].addEventListener('click', () => {
                const canvas = signatureContainers[type];
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                canvas.removeAttribute('data-signed');
                checkAllSignatures();
            });
        });
    });

    // Inisialisasi totalScore untuk setiap kategori
    let totalScore = {};
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("[data-detail-id]").forEach(section => {
            const detailId = section.getAttribute("data-detail-id");
            totalScore[detailId] = 0; // Set nilai awal 0 untuk setiap kategori
        });
    });

    function tambahTertuduh(detailId) {
        const tertuduhInput = document.getElementById(`tertuduh-${detailId}`);
        const temuanInput = document.getElementById(`temuan-${detailId}`);
        const dataList = document.getElementById(`data-list-${detailId}`);
        const inputScore = document.getElementById(`inputScore-${detailId}`);

        const tertuduh = tertuduhInput.value.trim();
        const temuan = temuanInput.value.trim();

        // Jika kedua input kosong, tampilkan alert
        if (tertuduh === "" && temuan === "") {
            alert("Minimal satu input harus diisi!");
            return;
        }

        const temuanValue = temuan !== "" ? parseInt(temuan, 10) : 0; // Jika temuan kosong, anggap 0

        // Buat unique ID untuk entri ini
        const entryId = Date.now().toString();

        // Buat input hidden baru untuk setiap entri
        const form = document.getElementById('auditForm');
        
        // Buat input hidden baru untuk tertuduh
        const newTertuduhInput = document.createElement('input');
        newTertuduhInput.type = 'hidden';
        newTertuduhInput.name = `tertuduh_${detailId}[]`;
        newTertuduhInput.value = tertuduh;
        newTertuduhInput.dataset.entryId = entryId;
        form.appendChild(newTertuduhInput);
        
        // Buat input hidden baru untuk temuan
        const newTemuanInput = document.createElement('input');
        newTemuanInput.type = 'hidden';
        newTemuanInput.name = `temuan_${detailId}[]`;
        newTemuanInput.value = temuan;
        newTemuanInput.dataset.entryId = entryId;
        form.appendChild(newTemuanInput);

        // Buat elemen baru untuk daftar
        const dataItem = document.createElement("div");
        dataItem.classList.add("flex", "justify-between", "items-center", "bg-gray-100", "p-2", "rounded-lg", "shadow-sm");
        dataItem.dataset.entryId = entryId;

        dataItem.innerHTML = `
            <span class="text-gray-700">${tertuduh || "(Tanpa Nama)"} - Temuan: ${temuanValue}</span>
            <button type="button" onclick="hapusTertuduh(this, ${temuanValue}, '${detailId}', '${entryId}')" class="bg-red-500 text-white px-2 py-1 rounded-lg hover:bg-red-700">Hapus</button>
        `;

        dataList.appendChild(dataItem);

        // Update score hanya jika temuan ada
        if (temuanValue > 0) {
            totalScore[detailId] += temuanValue;
            inputScore.value = totalScore[detailId];
        }

        // Reset input setelah ditambahkan
        tertuduhInput.value = "";
        temuanInput.value = "";
    }

    function hapusTertuduh(button, temuanValue, detailId, entryId) {
        // Hapus elemen dari UI
        button.parentElement.remove();

        // Hapus input hidden terkait
        document.querySelector(`input[name="tertuduh_${detailId}[]"][data-entry-id="${entryId}"]`).remove();
        document.querySelector(`input[name="temuan_${detailId}[]"][data-entry-id="${entryId}"]`).remove();

        // Kurangi score saat menghapus data
        totalScore[detailId] -= temuanValue;
        document.getElementById(`inputScore-${detailId}`).value = totalScore[detailId];
    }

    function handleFileSelect(event, detailId) {
        const files = event.target.files;
        const previewContainer = document.getElementById(`image-preview-container-${detailId}`);
        
        // Create a separate input for each file to prevent overwriting
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            // Create a unique ID for this file
            const fileId = Date.now() + '-' + i;
            
            reader.onload = function(e) {
                // Create container for image preview
                const imageWrapper = document.createElement('div');
                imageWrapper.className = 'relative';
                imageWrapper.dataset.fileId = fileId;
                
                // Create the image preview
                const image = document.createElement('img');
                image.src = e.target.result;
                image.className = 'w-32 h-32 object-cover rounded-lg shadow-md';
                
                // Create delete button
                const deleteButton = document.createElement('button');
                deleteButton.innerHTML = '&times;';
                deleteButton.className = 'absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center';
                deleteButton.type = 'button';
                
                // Create a hidden file input for each image
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'file';
                hiddenInput.name = `image_path_${detailId}[]`;
                hiddenInput.className = 'hidden';
                hiddenInput.dataset.fileId = fileId;
                
                // Create a DataTransfer object to add the file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                hiddenInput.files = dataTransfer.files;
                
                // Add delete functionality
                deleteButton.onclick = function() {
                    previewContainer.removeChild(imageWrapper);
                    document.querySelector(`input[data-file-id="${fileId}"]`).remove();
                };
                
                // Add elements to the container
                imageWrapper.appendChild(image);
                imageWrapper.appendChild(deleteButton);
                imageWrapper.appendChild(hiddenInput);
                
                previewContainer.appendChild(imageWrapper);
            };
            
            reader.readAsDataURL(file);
        }
        
        // Clear the original file input to prevent duplication
        event.target.value = "";
    }
</script>
@endpush