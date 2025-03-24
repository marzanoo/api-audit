<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Audit App</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo_wag.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 w-full max-w-md text-center">
        <!-- Logo -->
        <img src="{{ asset('logo/logo_wag.png') }}" class="w-36 h-36 mx-auto mb-6" alt="Logo">

        <!-- Title -->
        <h2 class="text-2xl font-bold mb-6">Verifikasi Email</h2>

        @if (session('aktivasi_error'))
            <p class="text-red-500 mb-4">{{ session('aktivasi_error') }}</p>
        @endif

        @if (session('aktivasi_success'))
            <p class="text-green-500 mb-4">{{ session('aktivasi_success') }}</p>
        @endif

        <!-- Form -->
        <form action="{{ url('verify-otp-aktivasi') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input type="email" name="email" value="{{ session('otp_email') }}" hidden>
            </div>

            <div class="mb-4">
                <input type="number" name="otp" placeholder="Masukkan Kode OTP" required maxlength="6"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 appearance-none">
            </div>

            <!-- Verifikasi Button -->
            <button type="submit" class="w-full bg-black text-white py-2 rounded-3xl hover:bg-gray-800">
                Verifikasi
            </button>
        </form>
         <!-- Resend Link -->
         <div class="flex justify-center mb-4 text-sm mt-2">
            <span>Belum menerima OTP?</span>
        
            <form action="{{ route('resend-otp-aktivasi') }}" id="resendOtpForm" method="POST">
                @csrf
                <input type="email" name="email" value="{{ session('otp_email') }}" hidden>
            </form>
            <a href="#" id="resendOtpBtn" class="text-blue-500 font-semibold ml-1">Kirim Ulang</a>
            <span id="countdown" class="text-gray-500 ml-2"></span>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const resendOtpBtn = document.getElementById("resendOtpBtn");
            const countdownSpan = document.getElementById("countdown");
            let cooldownTime = localStorage.getItem("otpCooldown") ? parseInt(localStorage.getItem("otpCooldown")) : 0;

            function startCooldown() {
                resendOtpBtn.classList.add("pointer-events-none", "text-gray-400");
                resendOtpBtn.classList.remove("text-blue-500");
                countdownSpan.textContent = `(${cooldownTime}s)`;

                let interval = setInterval(() => {
                    cooldownTime--;
                    localStorage.setItem("otpCooldown", cooldownTime);
                    countdownSpan.textContent = `(${cooldownTime}s)`;

                    if (cooldownTime <= 0) {
                        clearInterval(interval);
                        resendOtpBtn.classList.remove("pointer-events-none", "text-gray-400");
                        resendOtpBtn.classList.add("text-blue-500");
                        countdownSpan.textContent = "";
                        localStorage.removeItem("otpCooldown");
                    }
                }, 1000);
            }

            if (cooldownTime > 0) {
                startCooldown();
            }

            resendOtpBtn.addEventListener("click", function (event) {
                event.preventDefault();
                if (cooldownTime > 0) return; // Mencegah spam klik

                cooldownTime = 30;
                localStorage.setItem("otpCooldown", cooldownTime);
                startCooldown();
                document.getElementById("resendOtpForm").submit(); // Submit form
            });
        });
    </script>

</body>
</html>
