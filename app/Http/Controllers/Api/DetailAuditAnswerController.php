<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditAnswer;
use App\Models\DetailAuditAnswer;
use App\Models\DetailAuditeeAnswer;
use App\Models\DetailFotoAuditAnswer;
use App\Models\DetailSignatureAuditAnswer;
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

    public function showAuditAnswer($auditAnswerId) {}

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

        // Handle tertuduh data - now supports multiple tertuduh
        if ($request->has('tertuduh')) {
            // First, clear existing tertuduh data to avoid duplicates
            DetailAuditeeAnswer::where('detail_audit_answer_id', $detailAuditAnswerId)->delete();

            // Check if tertuduh is a string or array
            $tertuduhData = is_array($request->tertuduh) ? $request->tertuduh : [$request->tertuduh];

            foreach ($tertuduhData as $tertuduh) {
                if (empty($tertuduh)) continue;

                // Search for employee by name
                $employee = Karyawan::where('emp_name', 'like', '%' . $tertuduh . '%')->first();

                if ($employee) {
                    // Create with employee ID
                    DetailAuditeeAnswer::create([
                        'detail_audit_answer_id' => $detailAuditAnswerId,
                        'auditee' => $employee->emp_id // Store the employee ID as foreign key
                    ]);
                } else {
                    DetailAuditeeAnswer::create([
                        'detail_audit_answer_id' => $detailAuditAnswerId,
                        'auditee_name' => $tertuduh // Store as a string
                    ]);
                }
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


    public function uploadSignature(Request $request)
    {
        // Validate the request
        $request->validate([
            'audit_answer_id' => 'required',
            'auditor_signature' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'auditee_signature' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'facilitator_signature' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {
            // Process auditor signature
            $auditorFile = $request->file('auditor_signature');
            $auditorFileName = time() . '_auditor_' . $auditorFile->getClientOriginalName();
            $auditorFilePath = $auditorFile->storeAs('signatures', $auditorFileName, 'public');

            // Process auditee signature
            $auditeeFile = $request->file('auditee_signature');
            $auditeeFileName = time() . '_auditee_' . $auditeeFile->getClientOriginalName();
            $auditeeFilePath = $auditeeFile->storeAs('signatures', $auditeeFileName, 'public');

            // Process facilitator signature
            $facilitatorFile = $request->file('facilitator_signature');
            $facilitatorFileName = time() . '_facilitator_' . $facilitatorFile->getClientOriginalName();
            $facilitatorFilePath = $facilitatorFile->storeAs('signatures', $facilitatorFileName, 'public');

            // Create signature record with all three signatures
            $detailSignature = new DetailSignatureAuditAnswer();
            $detailSignature->audit_answer_id = $request->audit_answer_id;
            $detailSignature->auditor_signature = $auditorFilePath;
            $detailSignature->auditee_signature = $auditeeFilePath;
            $detailSignature->facilitator_signature = $facilitatorFilePath;
            $detailSignature->save();

            return response()->json([
                'message' => 'Tanda tangan berhasil disimpan',
                'data' => [
                    'id' => $detailSignature->id,
                    'auditor_signature' => $auditorFilePath,
                    'auditee_signature' => $auditeeFilePath,
                    'facilitator_signature' => $facilitatorFilePath
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan tanda tangan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getAuditAnswer($auditAnswerId)
    {
        $data = DetailAuditAnswer::with([
            'variabel.temaForm.form',
            'detailAuditeeAnswer',
            'detailFotoAuditAnswer'
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
                    'auditees' => $detail->detailAuditeeAnswer->map(function ($auditee) {
                        return [
                            'id' => $auditee->id,
                            'auditee' => $auditee->auditee ?: $auditee->auditee_name
                        ];
                    }),
                    'images' => $detail->detailFotoAuditAnswer->map(function ($foto) {
                        return [
                            'id' => $foto->id,
                            'image_path' => $foto->image_path
                        ];
                    }),
                ];
            });

        $signatures = DetailSignatureAuditAnswer::where('audit_answer_id', $auditAnswerId)->first();

        return response()->json([
            'data' => $data,
            'auditor_signature' => $signatures ? $signatures->auditor_signature : null,
            'auditee_signature' => $signatures ? $signatures->auditee_signature : null,
            'facilitator_signature' => $signatures ? $signatures->facilitator_signature : null
        ], 200);
    }
}
