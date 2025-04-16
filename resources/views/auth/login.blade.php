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

        @if (session('reset_success'))
            <p class="text-green-500 mb-4">{{ session('reset_success') }}</p>
        @endif

        <!-- Form -->
        <form action="{{ url('login') }}" method="POST">
            @csrf

            <input type="hidden" name="device_id" id="device_id">

            <div class="mb-4">
                <input type="text" name="username" placeholder="Username" required 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="mb-2">
                <input type="password" name="password" placeholder="Password" required 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex mb-4 text-sm">
                <a href="{{ route('reset-password') }}" class="text-blue-500 font-semibold ml-1">Lupa Password?</a>
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
            // Try to get device ID from multiple sources
            let deviceId = localStorage.getItem('device_id') || 
                          getCookie('device_id');
            
            if (!deviceId) {
                // Create a more stable fingerprint using multiple browser properties
                const screenPrint = `${screen.height}x${screen.width}x${screen.colorDepth}`;
                const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                const languages = navigator.languages ? navigator.languages.join(',') : navigator.language;
                const cpuCores = navigator.hardwareConcurrency || 'unknown';
                const deviceMemory = navigator.deviceMemory || 'unknown';
                const userAgent = navigator.userAgent;
                
                // Create a hashcode from these combined values
                const rawFingerprint = `${screenPrint}|${timeZone}|${languages}|${cpuCores}|${deviceMemory}|${userAgent}`;
                deviceId = hashCode(rawFingerprint);
                
                // Store in multiple locations
                localStorage.setItem('device_id', deviceId);
                setCookie('device_id', deviceId, 365 * 5); // 5 years
            }
            
            document.getElementById("device_id").value = deviceId;
            
            // Helper functions
            function hashCode(str) {
                let hash = 0;
                for (let i = 0; i < str.length; i++) {
                    const char = str.charCodeAt(i);
                    hash = ((hash << 5) - hash) + char;
                    hash = hash & hash; // Convert to 32bit integer
                }
                return hash.toString(36) + Date.now().toString(36).substring(2, 5);
            }
            
            function setCookie(name, value, days) {
                const d = new Date();
                d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
                const expires = "expires=" + d.toUTCString();
                document.cookie = name + "=" + value + ";" + expires + ";path=/";
            }
            
            function getCookie(name) {
                const cname = name + "=";
                const decodedCookie = decodeURIComponent(document.cookie);
                const ca = decodedCookie.split(';');
                for(let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(cname) == 0) {
                        return c.substring(cname.length, c.length);
                    }
                }
                return "";
            }
        });
    </script>
</body>
</html>