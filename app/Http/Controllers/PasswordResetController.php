<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function index()
    {
        return view('auth.resetPassword.index');
    }

    public function inputOtp()
    {
        return view('auth.resetPassword.input-otp');
    }
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('reset-password')->with(['reset_error' => 'Email tidak ditemukan']);
        }

        $user->generateOtpReset();

        session(['reset_email' => $user->email]);

        return redirect()->route('reset-password-otp')->with(['reset_success' => 'OTP berhasil dikirim ke email anda.']);
    }

    public function newPassword()
    {
        return view('auth.resetPassword.new-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::where('email', $request->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with(['reset_success' => 'Password berhasil direset']);
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with(['reset_error' => 'Email tidak ditemukan'], 404);
        }

        if ($user->otp != $request->otp || Carbon::now()->gt($user->otp_expires_at)) {
            return redirect()->back()->with(['reset_error' => 'OTP salah atau kadaluarsa'], 400);
        }

        // hapus otp setelah verifikasi berhasil
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return redirect()->route('reset-new-password')->with(['reset_success' => 'Verifikasi OTP berhasil, silahkan reset password.']);
    }

    public function resendOtpReset(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with(['aktivasi_error' => 'Email tidak ditemukan'], 404);
        }

        $user->generateOtpReset();

        return back()->with(['aktivasi_success' => 'OTP berhasil dikirim ulang ke email anda.']);
    }
}
