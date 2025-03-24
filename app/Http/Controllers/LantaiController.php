<?php

namespace App\Http\Controllers;

use App\Models\Lantai;
use Illuminate\Http\Request;

class LantaiController extends Controller
{
    public function index()
    {
        $lantai = Lantai::orderBy('lantai', 'ASC')->get();
        return view('admin.konfigurasi.lantai.index', compact('lantai'));
    }

    public function addLantai()
    {
        return view('admin.konfigurasi.lantai.add-lantai');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lantai' => 'required|numeric'
        ]);
        $lantai = Lantai::where('lantai', $request->lantai)->first();

        if ($lantai) {
            return back()->with(['lantai_error' => 'Lantai sudah ada']);
        }

        if ($request->lantai < 1 || $request->lantai > 8) {
            return back()->with(['lantai_error' => 'Lantai tidak valid']);
        }

        $lantai = Lantai::create([
            'lantai' => $request->lantai
        ]);

        return redirect('lantai')->with(['lantai_success' => "Lantai $request->lantai berhasil ditambahkan"]);
    }

    public function destroy($id)
    {
        $lantai = Lantai::find($id);

        if (!$lantai) {
            return back()->with(['lantai_error' => 'Lantai tidak ditemukan']);
        }

        $lantai->delete();

        return back()->with(['lantai_success' => 'Lantai berhasil dihapus']);
    }
}
