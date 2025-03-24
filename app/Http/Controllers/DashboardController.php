<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Lantai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with(['login_error' => 'Silakan login terlebih dahulu.']);
        }

        $user = auth()->user();

        if ($user->role == 1) {
            return view('admin.admin-home');
        } else if ($user->role == 2) {
            return view('steerco.steerco-home');
        } else if ($user->role == 3) {
            return view('auditor.auditor-home');
        } else {
            Auth::logout();
            return redirect()->route('login')->with(['login_error' => 'Role tidak ditemukan.']);
        }
    }

    public function konfigurasiView()
    {
        $total_area = Area::count();
        $total_lantai = Lantai::count();
        return view('admin.konfigurasi.index', compact('total_lantai', 'total_area'));
    }
}
