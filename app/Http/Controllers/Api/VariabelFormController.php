<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VariabelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VariabelFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $variabel = VariabelForm::with('temaForm:id,tema')->where('tema_form_id', $id)->get();
        return response()->json([
            'message' => 'Variabel berhasil diambil',
            'data' => $variabel
        ], 200);
    }

    public function getTotalVariabel()
    {
        $variabel = VariabelForm::count();
        return response()->json([
            'message' => 'Total variabel berhasil diambil',
            'total' => $variabel
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tema_form_id' => 'required',
            'variabel' => 'required',
            'standar_variabel' => 'required',
            'standar_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if (VariabelForm::where('tema_form_id', $request->tema_form_id)->where('variabel', $request->variabel)->exists()) {
            return response()->json([
                'message' => 'Variabel sudah ada',
            ]);
        }
        $path = null;
        if ($request->hasFile('standar_foto')) {
            $path = $request->file('standar_foto')->store('standards', 'public');

            $variabel = VariabelForm::create([
                'tema_form_id' => $request->tema_form_id,
                'variabel' => $request->variabel,
                'standar_variabel' => $request->standar_variabel,
                'standar_foto' => $path,
            ]);

            return response()->json([
                'message' => 'Variabel berhasil ditambahkan',
                'data' => $variabel,
                'photo_url' => $path ? asset('storage/' . $path) : null
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $variabel = VariabelForm::find($id);
        if (!$variabel) {
            return response()->json([
                'message' => 'Variabel tidak ditemukan',
            ]);
        }
        return response()->json([
            'message' => 'Variabel berhasil diambil',
            'data' => $variabel,
            'photo_url' => $variabel->standar_foto ? asset('storage/' . $variabel->standar_foto) : null
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         'tema_form_id' => 'required',
    //         'variabel' => 'required',
    //         'standar_variabel' => 'required',
    //         'standar_foto' => $request->hasFile('standar_foto') ? 'required|image|mimes:jpeg,png,jpg,gif,svg' : '',
    //     ]);

    //     dd(request()->all());

    //     if (VariabelForm::where('tema_form_id', $request->tema_form_id)->where('variabel', $request->variabel)->where('id', '!=', $id)->exists()) {
    //         return response()->json([
    //             'message' => 'Variabel sudah ada',
    //             'data' => null,
    //             'photo_url' => null
    //         ]);
    //     }

    //     $variabel = VariabelForm::find($id);
    //     if (!$variabel) {
    //         return response()->json([
    //             'message' => 'Variabel tidak ditemukan',
    //             'data' => null,
    //             'photo_url' => null
    //         ], 404);
    //     }

    //     $variabel->tema_form_id = $request->tema_form_id;
    //     $variabel->variabel = $request->variabel;
    //     $variabel->standar_variabel = $request->standar_variabel;

    //     // Only update photo if a new one is uploaded
    //     if ($request->hasFile('standar_foto')) {
    //         $path = $request->file('standar_foto')->store('standards', 'public');
    //         $variabel->standar_foto = $path;
    //     }

    //     $variabel->save();

    //     return response()->json([
    //         'message' => 'Variabel berhasil diubah',
    //         'data' => $variabel,
    //         'photo_url' => $variabel->standar_foto ? asset('storage/' . $variabel->standar_foto) : null
    //     ], 200);
    // }
    public function update(Request $request, $id)
    {
        // Gunakan '_method' agar PUT bisa diterima saat dikirim sebagai POST
        if ($request->has('_method') && $request->_method === 'PUT') {
            $request->merge(['_method' => 'PUT']);
        }

        // Validasi input
        $request->validate([
            'tema_form_id' => 'required|integer',
            'variabel' => 'required|string',
            'standar_variabel' => 'required|string',
            'standar_foto' => 'nullable|image|mimes:jpg,jpeg,png', // Pastikan format gambar benar
        ]);

        // Cari data berdasarkan ID
        $variabel = VariabelForm::findOrFail($id);

        // Update data
        $variabel->tema_form_id = $request->tema_form_id;
        $variabel->variabel = $request->variabel;
        $variabel->standar_variabel = $request->standar_variabel;

        // Cek apakah ada file yang diunggah
        if ($request->hasFile('standar_foto')) {
            // Hapus file lama jika ada
            if ($variabel->standar_foto) {
                Storage::disk('public')->delete($variabel->standar_foto);
            }

            // Simpan file baru di 'storage/app/public/standards'
            $path = $request->file('standar_foto')->store('standards', 'public');
            $variabel->standar_foto = $path; // Simpan path ke database
        }

        // Simpan perubahan
        $variabel->save();

        return response()->json([
            'message' => 'Data berhasil diperbarui!',
            'data' => $variabel,
            'photo_url' => $variabel->standar_foto ? asset('storage/' . $variabel->standar_foto) : null
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $variabel = VariabelForm::find($id);
        $variabel->delete();
        return response()->json([
            'message' => 'Variabel berhasil dihapus',
        ]);
    }
}
