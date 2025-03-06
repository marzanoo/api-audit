<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            // 'device_id' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Auth::attempt($request->only('username', 'password'))) {
            return back()->with(['login_error' => 'Username atau password salah.']);
        }

        // if (!$user->device_id) {
        //     $user->device_id = $request->device_id;
        //     $user->save();
        // } elseif ($user->device_id !== $request->device_id) {
        //     Auth::logout();
        //     return back()->with(['login_error' => 'Akun hanya bisa digunakan di perangkat pertama yang terdaftar.']);
        // }

        if (!$user->email_verified_at) {
            Auth::logout();
            return back()->with(['login_error' => 'Email belum diverifikasi.']);
        }

        return redirect()->route('dashboard');
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
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silahkan login.');
    }

    // Menampilkan dashboard setelah login
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('dashboard');
    }

    // Logout user
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }
}
