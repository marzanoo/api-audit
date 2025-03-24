<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo_wag.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 w-full max-w-md text-center">
        <!-- Logo -->
        <img src="{{ asset('logo/logo_wag.png') }}" class="w-36 h-36 mx-auto mb-6" alt="Logo">

        <!-- Title -->
        <h2 class="text-2xl font-bold mb-6">Login</h2>

        @if (session('login_error'))
            <p class="text-red-500 mb-4">{{ session('login_error') }}</p>
        @endif

        @if (session('aktivasi_success'))
            <p class="text-green-500 mb-4">{{ session('aktivasi_success') }}</p>
        @endif

        <!-- Form -->
        <form action="{{ url('login') }}" method="POST">
            @csrf

            <input type="hidden" name="device_id" id="device_id">

            <div class="mb-4">
                <input type="text" name="username" placeholder="Username" required 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="mb-4">
                <input type="password" name="password" placeholder="Password" required 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Register Link -->
            <div class="flex justify-center mb-4 text-sm">
                <span>Belum punya akun?</span>
                <a href="{{ url('register') }}" class="text-blue-500 font-semibold ml-1">Register</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full bg-black text-white py-2 rounded-3xl hover:bg-gray-800">
                Login
            </button>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let deviceId = localStorage.getItem('device_id');
        
            if (!deviceId) {
                deviceId = (Math.random() + 1).toString(36).substring(7) + Date.now();
                localStorage.setItem('device_id', deviceId);
            }
        
            document.getElementById("device_id").value = deviceId;
        });
    </script>
</body>
</html>
