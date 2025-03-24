<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Karyawan;
use App\Models\Lantai;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $area = Area::with('lantai:id,lantai', 'karyawans:emp_id,emp_name')->get();
        return view('admin.konfigurasi.area.index', compact('area'));
    }

    public function addArea()
    {
        $karyawan = Karyawan::where('remarks', 'LIKE', '%AUDITEE%')->get();
        $lantai = Lantai::orderBy('lantai', 'ASC')->get();
        return view('admin.konfigurasi.area.add-area', compact('karyawan', 'lantai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lantai' => 'required',
            'area' => 'required',
            'pic_area' => 'required',
        ]);

        $pic_area = Area::where('pic_area', $request->pic_area)->first();
        if ($pic_area) {
            return back()->with(['area_error' => 'PIC area sudah ada di area lain']);
        }

        $existingArea = Area::where('area', $request->area)->first();
        if ($existingArea) {
            return back()->with(['area_error' => "Nama area '$request->area' sudah terdaftar di lantai lain atau lantai yang sama"]);
        }

        $area = Area::create([
            'lantai_id' => $request->lantai,
            'area' => $request->area,
            'pic_area' => $request->pic_area,
        ]);

        return back()->with(['area_success' => "Area $request->area berhasil ditambahkan"]);
    }

    public function editArea($id)
    {
        $area = Area::with('lantai:id,lantai', 'karyawans:emp_id,emp_name')->find($id);
        $karyawan = Karyawan::where('remarks', 'LIKE', '%AUDITEE%')->get();
        $lantai = Lantai::orderBy('lantai', 'ASC')->get();
        return view('admin.konfigurasi.area.edit-area', compact('area', 'karyawan', 'lantai'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'lantai' => 'required',
                'area' => 'required',
                'pic_area' => 'required',
            ]);
            $pic_area = Area::where('pic_area', $request->pic_area)->where('id', '!=', $id)->first();
            if ($pic_area) {
                return back()->with(['area_error' => 'PIC area sudah ada di area lain']);
            }
            $area = Area::find($id);
            $area->lantai_id = $request->lantai;
            $area->area = $request->area;
            $area->pic_area = $request->pic_area;
            $area->save();

            return redirect('area')->with(['area_success' => 'Area berhasil diupdate']);
        } catch (\Exception $e) {
            return back()->with(['area_error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $area = Area::find($id);
        if (!$area) {
            return redirect('area')->with(['area_error' => 'Area tidak ditemukan']);
        }
        $area->delete();
        return back()->with(['area_success' => 'Area berhasil dihapus']);
    }
}
