<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Audit App</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo_wag.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 w-full max-w-md text-center">
        <!-- Logo -->
        <img src="{{ asset('logo/logo_wag.png') }}" class="w-36 h-36 mx-auto mb-6" alt="Logo">

        <!-- Title -->
        <h2 class="text-2xl font-bold mb-6">Reset Password</h2>

        @if (session('reset_error'))
            <p class="text-red-500 mb-4">{{ session('reset_error') }}</p>
        @endif

        @if (session('reset_success'))
            <p class="text-green-500 mb-4">{{ session('reset_success') }}</p>
        @endif

        <!-- Form -->
        <form action="{{ route('reset-password-otp') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input type="email" name="email" placeholder="Masukkan Email" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 appearance-none">
            </div>

            <!-- Verifikasi Button -->
            <button type="submit" class="w-full bg-black text-white py-2 rounded-3xl hover:bg-gray-800">
                Kirim Kode OTP
            </button>
        </form>
    </div>
</body>
</html>
