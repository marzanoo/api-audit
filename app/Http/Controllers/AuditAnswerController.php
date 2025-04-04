<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AuditAnswer;
use App\Models\DetailAuditAnswer;
use App\Models\VariabelForm;
use Illuminate\Http\Request;

class AuditAnswerController extends Controller
{
    public function showFormAudit()
    {
        $area = Area::with('lantai:id,lantai', 'karyawans:emp_id,emp_name')->get();
        $auditAnswer = AuditAnswer::with('area:id,area', 'auditor:id,name')->get();
        return view('auditor.form-audit.index', compact('auditAnswer', 'area'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'auditor_id' => 'required',
            'area' => 'required',
        ]);

        $auditAnswer = AuditAnswer::create([
            'tanggal' => $request->tanggal,
            'auditor_id' => $request->auditor_id,
            'area_id' => $request->area,
            'total_score' => 0,
        ]);

        $auditAnswerId = $auditAnswer->id;

        $variabelForms = VariabelForm::all();

        foreach ($variabelForms as $variabel) {
            DetailAuditAnswer::create([
                'audit_answer_id' => $auditAnswerId,
                'variabel_form_id' => $variabel->id,
                'score' => 0,
            ]);
        }

        return redirect()->route('detail-audit-answer', $auditAnswerId)->with('form_audit_success', 'Form audit berhasil dibuat');
    }

    public function deleteAuditForm($id)
    {
        $auditAnswer = AuditAnswer::find($id);
        $auditAnswer->delete();
        return redirect()->route('form-audit')->with('form_audit_success', 'Form audit berhasil dihapus');
    }
}
