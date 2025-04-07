<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AuditAnswer;
use App\Models\Lantai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $total_area = Area::count();
        if (!auth()->check()) {
            return redirect()->route('login')->with(['login_error' => 'Silakan login terlebih dahulu.']);
        }

        $user = auth()->user();

        if ($user->role == 1) {
            $total_audit = $this->totalAudit();
            return view('admin.admin-home', compact('total_audit'));
        } else if ($user->role == 2) {
            $total_audit = $this->totalAudit();
            return view('steerco.steerco-home', compact('total_audit'));
        } else if ($user->role == 3) {
            $total_audit = $this->totalAuditByAuditor();
            return view('auditor.auditor-home', compact('total_area', 'total_audit'));
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

    public function totalAuditByAuditor()
    {
        $total_audit = AuditAnswer::where('auditor_id', Auth::user()->id)->count();
        return $total_audit;
    }

    public function totalAudit()
    {
        $total_audit = AuditAnswer::count();
        return $total_audit;
    }
}
