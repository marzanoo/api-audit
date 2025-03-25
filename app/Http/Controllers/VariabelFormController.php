<?php

namespace App\Http\Controllers;

use App\Models\VariabelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VariabelFormController extends Controller
{
    public function index($id)
    {
        $temaFormId = $id;
        $variabel = VariabelForm::with('temaForm:id,tema')->where('tema_form_id', $id)->get();
        return view('admin.konfigurasi.form.tema.variabel.index', compact('variabel', 'temaFormId'));
    }

    public function addVariabelForm($id)
    {
        $temaFormId = $id;
        return view('admin.konfigurasi.form.tema.variabel.add-variabel', compact('temaFormId'));
    }

    public function store(Request $request, $id)
    {
        $temaFormId = $id;
        $request->validate([
            'variabel' => 'required',
            'standar_variabel' => 'required',
            'standar_foto' => 'nullable|image|mimes:jpg,jpeg,png', // Pastikan format gambar benar
        ]);

        $variabel = VariabelForm::where('tema_form_id', $temaFormId)->where('variabel', $request->variabel)->exists();
        if ($variabel) {
            return redirect()->route('add-variabel-form', $temaFormId)->with(['variabel_error' => 'Variabel sudah ada']);
        }

        if ($request->hasFile('standar_foto')) {
            $standar_foto = $request->file('standar_foto');
            $standar_foto_name = time() . '.' . $standar_foto->getClientOriginalExtension();

            // Simpan ke storage/app/public/standards/
            $standar_foto_path = $standar_foto->storeAs('standards', $standar_foto_name, 'public');
        }

        $variabel = VariabelForm::create([
            'tema_form_id' => $temaFormId,
            'variabel' => $request->variabel,
            'standar_variabel' => $request->standar_variabel,
            'standar_foto' => $standar_foto_path
        ]);



        return redirect()->route('variabel-form', $temaFormId)->with([
            'variabel_success' => 'Variabel berhasil ditambahkan'
        ]);
    }

    public function editVariabelForm($id)
    {
        $variabel = VariabelForm::find($id);
        return view('admin.konfigurasi.form.tema.variabel.edit-variabel', compact('variabel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'variabel' => 'required',
            'standar_variabel' => 'required',
            'standar_foto' => 'nullable|image|mimes:jpg,jpeg,png', // Pastikan format gambar benar
        ]);

        $variabel = VariabelForm::find($id);
        if (!$variabel) {
            return redirect()->route('variabel-form', $variabel->tema_form_id)->with([
                'variabel_error' => 'Variabel tidak ditemukan'
            ]);
        }

        if ($request->hasFile('standar_foto')) {
            $standar_foto = $request->file('standar_foto');
            $standar_foto_name = time() . '.' . $standar_foto->getClientOriginalExtension();

            // Simpan ke storage/app/public/standards/
            $standar_foto_path = $standar_foto->storeAs('standards', $standar_foto_name, 'public');

            // Hapus gambar lama jika ada
            if ($variabel->standar_foto) {
                Storage::disk('public')->delete($variabel->standar_foto);
            }
        }

        $variabel->variabel = $request->variabel;
        $variabel->standar_variabel = $request->standar_variabel;
        if ($request->hasFile('standar_foto')) {
            $variabel->standar_foto = $standar_foto_path;
        }
        $variabel->save();

        return redirect()->route('variabel-form', $variabel->tema_form_id)->with([
            'variabel_success' => 'Variabel berhasil diubah'
        ]);
    }

    public function destroy($id)
    {
        $variabel = VariabelForm::find($id);
        if (!$variabel) {
            return redirect()->route('variabel-form', $variabel->tema_form_id)->with([
                'variabel_error' => 'Variabel tidak ditemukan'
            ]);
        }
        if ($variabel->standar_foto) {
            Storage::disk('public')->delete($variabel->standar_foto);
        }
        $variabel->delete();
        return redirect()->route('variabel-form', $variabel->tema_form_id)->with([
            'variabel_success' => 'Variabel berhasil dihapus'
        ]);
    }
}
