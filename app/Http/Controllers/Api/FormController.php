<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index() {
        $forms = Form::all();
        return response()->json([
            'message' => 'Form berhasil diambil',
            'data' => $forms
        ]);
    }

    public function show($id) {
        $form = Form::find($id);
        return response()->json([
            'message' => 'Form berhasil diambil',
            'data' => $form
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'kategori' => 'required',
        ]);

        if (Form::where('kategori', $request->kategori)->exists()) {
            return response()->json([
                'message' => 'Kategori sudah ada',
            ]);
        }

        $form = Form::create([
            'kategori' => $request->kategori,
        ]);

        return response()->json([
            'message' => 'Form berhasil ditambahkan',
            'data' => $form
        ]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'kategori' => 'required',
        ]);

        if (Form::where('kategori', $request->kategori)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'message' => 'Kategori sudah ada',
            ]);
        }

        $form = Form::find($id);
        $form->kategori = $request->kategori;
        $form->save();

        return response()->json([
            'message' => 'Form berhasil diubah',
            'data' => $form
        ]);
    }

    public function destroy($id) {
        $form = Form::find($id);
        $form->delete();

        return response()->json([
            'message' => 'Form berhasil dihapus',
        ]);
    }
}
