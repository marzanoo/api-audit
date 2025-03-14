<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\AuditAnswer;
use App\Models\DetailAuditAnswer;
use App\Models\VariabelForm;
use Illuminate\Http\Request;

class AuditAnswerController extends Controller
{
    public function getTotalAuditByAuditor($id)
    {
        $total = AuditAnswer::where('auditor_id', $id)->count();
        return response()->json(['total' => $total]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required',
            'auditor_id' => 'required',
            'area_id' => 'required',
        ]);

        // 1. Insert ke tabel audit_answers
        $auditAnswer = AuditAnswer::create([
            'tanggal' => $request->tanggal,
            'auditor_id' => $request->auditor_id,
            'area_id' => $request->area_id,
            'total_score' => 0,
        ]);

        // 2. Ambil ID audit_answers yang baru saja dibuat
        $auditAnswerId = $auditAnswer->id;

        // 3. Ambil semua data dari tabel variabel_form
        $variabelForms = VariabelForm::all();

        // 4. Insert ke tabel detail_audit_answers
        foreach ($variabelForms as $variabel) {
            DetailAuditAnswer::create([
                'audit_answer_id' => $auditAnswerId, // Foreign key dari audit_answers
                'variabel_form_id' => $variabel->id, // Foreign key dari variabel_form
                'score' => null, // Score diisi nanti
            ]);
        }

        return response()->json([
            'message' => 'Audit answer dan detail berhasil disimpan.',
            'audit_answer' => $auditAnswer,
            'detail_audit_answers' => DetailAuditAnswer::where('audit_answer_id', $auditAnswerId)->get(),
        ]);
    }
}
