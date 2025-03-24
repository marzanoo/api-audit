<?php

namespace App\Http\Controllers;

use App\Models\TemaForm;
use Illuminate\Http\Request;

class TemaFormController extends Controller
{
    public function index($id)
    {
        $formId = $id;
        $tema = TemaForm::with('form:id,kategori')->where('form_id', $id)->get();
        return view('admin.konfigurasi.form.tema.index', compact('tema', 'formId'));
    }

    public function addTemaForm($id)
    {
        $formId = $id;
        return view('admin.konfigurasi.form.tema.add-tema', compact('formId'));
    }

    public function store(Request $request, $id)
    {
        $formId = $id;
        $request->validate([
            'tema' => 'required',
        ]);

        $tema = TemaForm::where('form_id', $request->form_id)->where('tema', $request->tema)->exists();
        if ($tema) {
            return redirect()->route('add-tema-form', $formId)->with(['tema_error' => 'Tema sudah ada']);
        }

        $tema = TemaForm::create([
            'form_id' => $formId,
            'tema' => $request->tema,
        ]);

        return redirect()->route('tema-form', $formId)->with(['tema_success' => 'Tema berhasil ditambahkan']);
    }

    public function editTemaForm($id)
    {
        $tema = TemaForm::find($id);
        return view('admin.konfigurasi.form.tema.edit-tema', compact('tema'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tema' => 'required',
        ]);

        $tema = TemaForm::where('tema', $request->tema)->where('id', '!=', $id)->exists();
        if ($tema) {
            return redirect()->route('edit-tema-form', $id)->with(['tema_error' => 'Tema sudah ada']);
        }

        $tema = TemaForm::find($id);
        $tema->tema = $request->tema;
        $tema->save();

        return redirect()->route('tema-form', $tema->form_id)->with(['tema_success' => 'Tema berhasil diubah']);
    }

    public function destroy($id)
    {
        $tema = TemaForm::find($id);
        if (!$tema) {
            return redirect()->route('tema-form', $tema->form_id)->with(['tema_error' => 'Tema tidak ditemukan']);
        }
        $tema->delete();
        return redirect()->route('tema-form', $tema->form_id)->with(['tema_success' => 'Tema berhasil dihapus']);
    }
}
