<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::all();
        return view('admin.konfigurasi.form.index', compact('forms'));
    }

    public function addForm()
    {
        return view('admin.konfigurasi.form.add-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required',
            'deskripsi' => 'required',
        ]);

        $form = Form::where('kategori', $request->kategori)->first();
        if ($form) {
            return back()->with(['form_error' => 'Form sudah ada']);
        }

        $form = Form::create([
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('form')->with(['form_success' => 'Form berhasil ditambahkan']);
    }

    public function destroy($id)
    {
        $form = Form::find($id);
        if (!$form) {
            return back()->with(['form_error' => 'Form tidak ditemukan']);
        }
        $form->delete();
        return back()->with(['form_success' => 'Form berhasil dihapus']);
    }
}
