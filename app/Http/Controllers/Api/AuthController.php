<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // public function index() {
    //     return view('aktivasi.aktivasi-berhasil');
    // }
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_id' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Username atau password salah'
            ], 401);
        }

        if (!$user->device_id) {
            $user->device_id = $request->device_id;
            $user->save();
        } else if ($user->device_id != $request->device_id) {
            return response()->json([
                'message' => 'Akun ini hanya bisa digunakan di perangkat yang pertama kali login'
            ], 403);
        }

        if ($user->email_verified_at == null) {
            return response()->json([
                'message' => 'Email belum diverifikasi',
                'email' => $user->email
            ], 403);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => now()->addMinutes(config('jwt.ttl'))->format('Y-m-d H:i:s'),
            'role' => $user->role,
            'email' => $user->email,
            'name' => $user->name,
            'message' => 'Login berhasil',
            'success' => true
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $karyawan = Karyawan::where('emp_id', $request->nik)->first();

        if (!$karyawan) {
            return response()->json([
                'message' => 'NIK tidak ditemukan'
            ], 404);
        }

        $email_username = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if ($email_username) {
            return response()->json([
                'message' => 'Email atau username sudah digunakan'
            ], 409);
        }

        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => 1,
            'password' => Hash::make($request->password)
        ]);

        $user->generateOtpAktivasi();

        return response()->json([
            'message' => 'Registrasi berhasil, silahkan cek email untuk verifikasi OTP',
            'user' => $user,
            'email' => $user->email
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        if ($user->otp != $request->otp || Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json([
                'message' => 'OTP salah atau kadaluarsa'
            ], 400);
        }

        // hapus otp setelah verifikasi berhasil
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->email_verified_at = Carbon::now();
        $user->save();

        return response()->json([
            'message' => 'Verifikasi OTP berhasil',
        ], 200);

        // return redirect()->route('aktivasi-berhasil');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        $user->generateOtpAktivasi();

        return response()->json([
            'message' => 'OTP berhasil dikirim ulang ke email anda',
        ], 200);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'message' => 'Logout berhasil'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Logout gagal'
            ], 500);
        }
    }

    public function refresh()
    {
        return response()->json([
            'access_token' => JWTAuth::refresh(),
            'token_type' => 'Bearer',
            'expires_at' => now()->addMinutes(config('jwt.ttl'))->format('Y-m-d H:i:s')
        ]);
    }

    public function resetDevice(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        $user->device_id = null;
        $user->save();

        return response()->json([
            'message' => 'Device berhasil direset, silahkan login kembali'
        ]);
    }
}
