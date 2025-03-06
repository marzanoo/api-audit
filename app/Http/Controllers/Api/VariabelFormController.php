<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VariabelForm;
use Illuminate\Http\Request;

class VariabelFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $variabel = VariabelForm::with('temaForm:id,tema')->get();
        return response()->json([
            'message' => 'Variabel berhasil diambil',
            'data' => $variabel
        ]);
    }

    public function getTotalVariabel() {
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
            'standar_foto' => 'required',
        ]);

        if (VariabelForm::where('tema_form_id', $request->tema_form_id)->where('variabel', $request->variabel)->exists()) {
            return response()->json([
                'message' => 'Variabel sudah ada',
            ]);
        }

        $variabel = VariabelForm::create([
            'tema_form_id' => $request->tema_form_id,
            'variabel' => $request->variabel,
            'standar_variabel' => $request->standar_variabel,
            'standar_foto' => $request->standar_foto,
        ]);

        return response()->json([
            'message' => 'Variabel berhasil ditambahkan',
            'data' => $variabel,
            'data_tema' => VariabelForm::with('temaForm:id,tema')->where('tema_form_id', $request->tema_form_id)->get([
            'tema_form_id',
            'variabel',
            'standar_variabel',
            'standar_foto'
            ])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $variabel = VariabelForm::find($id);
        return response()->json([
            'message' => 'Variabel berhasil diambil',
            'data' => $variabel
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tema_form_id' => 'required',
            'variabel' => 'required',
            'standar_variabel' => 'required',
            'standar_foto' => 'required',
        ]);

        if (VariabelForm::where('tema_form_id', $request->tema_form_id)->where('variabel', $request->variabel)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'message' => 'Variabel sudah ada',
            ]);
        }

        $variabel = VariabelForm::find($id);
        $variabel->tema_form_id = $request->tema_form_id;
        $variabel->variabel = $request->variabel;
        $variabel->standar_variabel = $request->standar_variabel;
        $variabel->standar_foto = $request->standar_foto;
        $variabel->save();

        return response()->json([
            'message' => 'Variabel berhasil diubah',
            'data' => $variabel
        ]);
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
