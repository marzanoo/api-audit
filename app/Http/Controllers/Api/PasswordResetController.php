<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        $user->generateOtpReset();

        return response()->json([
            'message' => 'Kode reset password berhasil dikirim ke email anda.'
        ]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->otp != $request->otp || Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json([
                'message' => 'OTP salah atau kadaluarsa'
            ], 400);
        }

        // hapus otp setelah verifikasi berhasil
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil direset'
        ]);
    }
}
