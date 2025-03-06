<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lantai;
use Illuminate\Http\Request;

class LantaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lantai = Lantai::orderBy('lantai', 'ASC')->get();
        return response()->json([
            'message' => 'Lantai berhasil diambil',
            'data' => $lantai
        ]);
    }

    public function getTotalLantai() {
        $lantai = Lantai::all();  
        return response()->json([
            'total_lantai' => $lantai->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lantai' => 'required|numeric'
        ]);

        $lantai = Lantai::where('lantai', $request->lantai)->first();

        if ($lantai) {
            return response()->json([
                'message' => 'Lantai sudah ada'
            ], 409);
        }

        if ($request->lantai < 1 || $request->lantai > 8) {
            return response()->json([
                'message' => 'Lantai tidak valid'
            ], 400);
        }

        $lantai = Lantai::create([
            'lantai' => $request->lantai
        ]);

        return response()->json([
            'message' => "Lantai $request->lantai berhasil ditambahkan"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lantai = Lantai::find($id);

        if (!$lantai) {
            return response()->json([
                'message' => 'Lantai tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'lantai' => $lantai
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         'lantai' => 'required|numeric'
    //     ]);

    //     $lantai = Lantai::find($id);

    //     if (!$lantai) {
    //         return response()->json([
    //             'message' => 'Lantai tidak ditemukan'
    //         ], 404);
    //     }

    //     if ($request->lantai < 1 || $request->lantai > 8) {
    //         return response()->json([
    //             'message' => 'Lantai tidak valid'
    //         ], 400);
    //     }

    //     $lantai->lantai = $request->lantai;
    //     $lantai->save();

    //     return response()->json([
    //         'lantai' => $lantai
    //     ]);
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lantai = Lantai::find($id);

        if (!$lantai) {
            return response()->json([
                'message' => 'Lantai tidak ditemukan'
            ], 404);
        }

        $lantai->delete();

        return response()->json([
            'message' => 'Lantai berhasil dihapus'
        ]);
    }
}
