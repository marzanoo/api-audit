<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (auth()->user()->role == 1) {
                return view('home.admin_home');
            } else if (auth()->user()->role == 2) {
                return view('home.steerco_home');
            } else if (auth()->user()->role == 3) {
                return view('home.auditor_home');
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors(['login_error' => 'Role anda tidak dikenali']);
            }
        } else {
            return redirect()->route('login')->withErrors(['login_error' => 'Anda belum login']);
        }
    }
}
