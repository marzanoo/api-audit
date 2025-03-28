<div class="fixed bottom-0 left-0 w-full bg-white border-t shadow-md">
    <div class="flex justify-around py-3">
        <!-- Beranda -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600">
            <i class="fas fa-home text-xl"></i>
            <span class="text-xs">Beranda</span>
        </a>

        <!-- Konfigurasi -->
        <a href="{{ route('konfigurasi') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600">
            <i class="fas fa-cogs text-xl"></i>
            <span class="text-xs">Konfigurasi</span>
        </a>

        <!-- Audit Office -->
        <a href="{{ route('audit-office-admin') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600">
            <i class="fas fa-clipboard-list text-xl"></i>
            <span class="text-xs">Audit Office</span>
        </a>
    </div>
</div>
