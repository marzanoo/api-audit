<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditAnswer;
use App\Models\DetailAuditAnswer;
use App\Models\DetailAuditeeAnswer;
use App\Models\DetailFotoAuditAnswer;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class DetailAuditAnswerController extends Controller
{
    public function getDetailAuditAnswer($auditAnswerId)
    {
        $data = DetailAuditAnswer::with([
            'variabel.temaForm.form'
        ])
            ->where('audit_answer_id', $auditAnswerId)->get()
            ->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'audit_answer_id' => $detail->audit_answer_id,
                    'variabel_form_id' => $detail->variabel_form_id,
                    'variabel' => $detail->variabel->variabel,
                    'standar_variabel' => $detail->variabel->standar_variabel,
                    'standar_foto' => $detail->variabel->standar_foto,
                    'tema' => $detail->variabel->temaForm->tema,
                    'kategori' => $detail->variabel->temaForm->form->kategori,
                    'score' => $detail->score,
                ];
            });

        return response()->json(['data' => $data], 200);
    }

    public function submitAnswer($auditAnswerId, $detailAuditAnswerId, Request $request)
    {
        $request->validate([
            'score' => 'required',
        ]);

        $detail = DetailAuditAnswer::findOrFail($detailAuditAnswerId);
        if ($detail->audit_answer_id != $auditAnswerId) {
            return response()->json(['message' => 'Data tidak valid'], 400);
        }

        $detail->score = $request->score;
        $detail->save();

        // Handle tertuduh data
        if ($request->has('tertuduh')) {
            // Search for employee by name
            $employee = Karyawan::where('emp_name', 'like', '%' . $request->tertuduh . '%')->first();

            if ($employee) {
                // Update or create with employee ID
                $auditee = DetailAuditeeAnswer::updateOrCreate(
                    ['detail_audit_answer_id' => $detailAuditAnswerId],
                    ['auditee' => $employee->emp_id] // Store the employee ID as foreign key
                );
            } else {
                $auditee = DetailAuditeeAnswer::updateOrCreate(
                    ['detail_audit_answer_id' => $detailAuditAnswerId],
                    ['auditee_name' => $request->tertuduh] // Store as a string
                );
            }
        }

        // Calculate total score
        $total_score = DetailAuditAnswer::where('audit_answer_id', $auditAnswerId)->sum('score');
        $audit_answer = AuditAnswer::findOrFail($auditAnswerId);
        $audit_answer->total_score = $total_score;
        $audit_answer->save();

        return response()->json(['message' => 'Score berhasil disimpan'], 200);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'detail_audit_answer_id' => 'required',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $file = $request->file('image_path');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Changed the storage path to uploads folder inside public/storage
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        $detailFoto = new DetailFotoAuditAnswer;
        $detailFoto->detail_audit_answer_id = $request->detail_audit_answer_id;
        $detailFoto->image_path = $filePath;
        $detailFoto->save();

        return response()->json([
            'message' => 'Foto berhasil diupload',
            'photo_id' => $detailFoto->id,
            'image_path' => $filePath
        ], 200);
    }
}
