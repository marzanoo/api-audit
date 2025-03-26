<div class="w-full flex justify-between items-center bg-white p-4 shadow-md fixed z-50">
    <img src="{{ asset('logo/logo_wag.png') }}" alt="Logo" class="h-10">
    
    <!-- Form Logout -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <!-- Tombol Logout -->
    <a href="#" onclick="document.getElementById('logout-form').submit();" 
       class="text-red-600 font-bold">Logout</a>
</div>
