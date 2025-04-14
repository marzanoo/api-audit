<?php

namespace App\Http\Controllers\Api;

use App\Exports\AuditAnswerExport;
use App\Http\Controllers\Controller;
use App\Models\AuditAnswer;
use App\Models\DetailAuditAnswer;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AuditOfficeAdminController extends Controller
{
    public function getDetailAuditAnswerForExport($id)
    {
        $auditAnswerId = $id;
        $data = DetailAuditAnswer::with([
            'variabel.temaForm.form',
            'detailAuditeeAnswer.userAuditee',
            'detailFotoAuditAnswer'
        ])->where('audit_answer_id', $auditAnswerId)->get();

        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $formattedData = $this->formatAuditData($data);
        $auditAnswer = AuditAnswer::with('area:id,area,lantai_id', 'auditor:id,name')
            ->where('id', $auditAnswerId)->first();
        $grade = $this->getGrade($auditAnswerId);

        return response()->json([
            'success' => true,
            'data' => $formattedData,
            'auditAnswer' => $auditAnswer,
            'grade' => $grade
        ]);
    }

    public function downloadAuditExcel($id)
    {
        $auditAnswerId = $id;
        $data = DetailAuditAnswer::with([
            'variabel.temaForm.form',
            'detailAuditeeAnswer.userAuditee',
            'detailFotoAuditAnswer'
        ])->where('audit_answer_id', $auditAnswerId)->get();

        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $formattedData = $this->formatAuditData($data);
        $auditAnswer = AuditAnswer::where('id', $auditAnswerId)->first();
        $grade = $this->getGrade($auditAnswerId);

        $fileName = 'Audit_Report_' . $auditAnswer->area->area . '_' . date('Y-m-d') . '.xlsx';
        $filePath = 'exports/' . $fileName;

        // Simpan file Excel ke storage
        Excel::store(new AuditAnswerExport($formattedData, $auditAnswer, $grade), $filePath, 'public');

        // Kembalikan URL untuk download
        $url = url(Storage::url($filePath));

        return response()->json([
            'success' => true,
            'message' => 'File Excel berhasil dibuat',
            'fileName' => $fileName,
            'fileUrl' => $url
        ]);
    }

    private function formatAuditData($data)
    {
        return $data->map(function ($detail) {
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
                        'name' => $auditee->userAuditee ? $auditee->userAuditee->emp_name : $auditee->auditee_name,
                        'temuan' => $auditee->temuan
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
    }

    private function getGrade($id)
    {
        $grade = "";
        $auditAnswer = AuditAnswer::where('id', $id)->first();
        if ($auditAnswer->total_score <= 2) {
            return $grade = "Diamond";
        } else if ($auditAnswer->total_score <= 4) {
            return $grade = "Platinum";
        } else if ($auditAnswer->total_score <= 6) {
            return $grade = "Gold";
        } else if ($auditAnswer->total_score <= 8) {
            return $grade = "Silver";
        } else if ($auditAnswer->total_score >= 9) {
            return $grade = "Bronze";
        } else {
            return $grade = "Unknown";
        }
    }
}
