<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TemaForm;
use Illuminate\Http\Request;

class TemaFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $tema = TemaForm::with('form:id,kategori')->where('form_id', $id)->get();
        return response()->json([
            'message' => 'Tema berhasil diambil',
            'data' => $tema
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'form_id' => 'required',
            'tema' => 'required',
        ]);

        if (TemaForm::where('form_id', $request->form_id)->where('tema', $request->tema)->exists()) {
            return response()->json([
                'message' => 'Tema sudah ada',
            ], 400);
        }

        $tema = TemaForm::create([
            'form_id' => $request->form_id,
            'tema' => $request->tema,
        ]);

        return response()->json([
            'message' => 'Tema berhasil ditambahkan',
            'data' => $tema
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tema = TemaForm::find($id);
        return response()->json([
            'message' => 'Tema berhasil diambil',
            'data' => $tema
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tema' => 'required',
        ]);

        if (TemaForm::where('tema', $request->tema)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'message' => 'Tema sudah ada',
            ], 400);
        }

        $tema = TemaForm::find($id);
        $tema->tema = $request->tema;
        $tema->save();

        return response()->json([
            'message' => 'Tema berhasil diubah',
            'data' => $tema
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tema = TemaForm::find($id);
        $tema->delete();
        return response()->json([
            'message' => 'Tema berhasil dihapus',
        ]);
    }
}
