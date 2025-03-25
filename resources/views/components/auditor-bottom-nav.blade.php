<div class="fixed bottom-0 left-0 w-full bg-white border-t shadow-md">
    <div class="flex justify-around py-3">
        <!-- Beranda -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600">
            <i class="fas fa-home text-xl"></i>
            <span class="text-xs">Beranda</span>
        </a>

        <!-- Isi Form -->
        <a href="{{ route('audit-answer') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600">
            <i class="fas fa-clipboard-list text-xl"></i>
            <span class="text-xs">Isi Form</span>
        </a>
    </div>
</div>
