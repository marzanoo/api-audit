<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailAuditAnswer;
use App\Models\DetailFotoAuditAnswer;
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
        $detail = DetailAuditAnswer::where('id', $detailAuditAnswerId)->andWhere('audit_answer_id', $auditAnswerId)->first();
        $detail->score = $request->score;
        $detail->save();

        $detail_foto = DetailFotoAuditAnswer::where('detail_audit_answer_id', $detailAuditAnswerId)->first();
        if ($detail_foto) {
            DetailFotoAuditAnswer::create([
                'detail_audit_answer_id' => $detailAuditAnswerId,
                'image_path' => $request->image_path
            ]);
        }
        return response()->json(['message' => 'Score berhasil disimpan'], 200);
    }
}
