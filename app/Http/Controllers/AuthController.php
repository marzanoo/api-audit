<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_id' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Auth::attempt($request->only('username', 'password'))) {
            return back()->with(['login_error' => 'Username atau password salah.']);
        }

        if (!$user->email_verified_at) {
            Auth::logout();
            return back()->with(['login_error' => 'Email belum diverifikasi.']);
        }

        $browserDeviceId = $request->device_id;

        if (!$user->device_id) {
            $user->device_id = $browserDeviceId;
            $user->save();
        } elseif ($user->device_id !== $browserDeviceId) {
            Auth::logout();
            return back()->with(['login_error' => 'Akun hanya bisa digunakan di perangkat pertama yang terdaftar.']);
        }

        // Extended cookie duration to 5 years
        Cookie::queue('device_id', $browserDeviceId, 60 * 24 * 365 * 5);

        return redirect()->route('dashboard')->with(['login_success' => 'Login berhasil.']);
    }

    // Menampilkan halaman registrasi
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi
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
            return back()->with(['register_error' => 'NIK tidak ditemukan']);
        }

        $email_username = User::where('username', $request->username)->orWhere('email', $request->username)->first();

        if ($email_username) {
            return back()->with(['register_error' => 'Email atau username sudah digunakan']);
        }

        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 3,
        ]);

        $user->generateOtpAktivasi();

        session(['otp_email' => $user->email]);

        return redirect()->route('verify-email')->with(['register_success' => 'Registrasi berhasil, silahkan login.']);
    }

    // Proses aktivasi akun
    public function verifyOtpAktivasi(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with(['aktivasi_error' => 'Email tidak ditemukan'], 404);
        }

        if ($user->otp != $request->otp || Carbon::now()->gt($user->otp_expires_at)) {
            return back()->with(['aktivasi_error' => 'OTP salah atau kadaluarsa'], 400);
        }

        // hapus otp setelah verifikasi berhasil
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->email_verified_at = Carbon::now();
        $user->save();

        return redirect()->route('login')->with(['aktivasi_success' => 'Verifikasi OTP berhasil, silahkan login.']);
    }

    public function resendOtpAktivasi(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with(['aktivasi_error' => 'Email tidak ditemukan'], 404);
        }

        $user->generateOtpAktivasi();

        return back()->with(['aktivasi_success' => 'OTP berhasil dikirim ulang ke email anda.']);
    }

    // Menampilkan halaman verifikasi email
    public function verifyEmail()
    {
        return view('aktivasi.verify-email');
    }

    // Logout user
    public function logout()
    {
        Auth::logout();
        Session::flush();

        // Hapus cookie
        Cookie::queue(Cookie::forget('device_id'));

        return redirect()->route('login');
    }
}
