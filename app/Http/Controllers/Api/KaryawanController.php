<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{

    public function index() {
        $karyawan = Karyawan::all();
        return response()->json([
            'message' => 'Karyawan berhasil diambil',
            'data' => $karyawan
        ]);
    }

    public function getKaryawanPic() {
        $karyawan = Karyawan::where('remarks', 'LIKE', '%AUDITEE%')->get();
        return response()->json([
            'message' => 'Karyawan berhasil diambil',
            'data' => $karyawan
        ]);
    }

    public function getKaryawan($nik) {
        $karyawan = Karyawan::where('emp_id', $nik)->first();

        if (!$karyawan) {
            return response()->json([
                'message' => 'NIK tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'name' => $karyawan->emp_name,
        ]);
    }
}
