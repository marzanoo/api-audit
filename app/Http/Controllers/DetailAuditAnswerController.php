<?php

namespace App\Http\Controllers;

use App\Models\DetailAuditAnswer;
use Illuminate\Http\Request;

class DetailAuditAnswerController extends Controller
{
    public function showFormAuditDetail($id)
    {
        $auditAnswerId = $id;
        $detailAuditAnswer = DetailAuditAnswer::with([
            'variabel.temaForm.form'
        ])->where('audit_answer_id', $auditAnswerId)->get()
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
        return view('auditor.form-audit.detail.index', compact('detailAuditAnswer'));
    }
}
