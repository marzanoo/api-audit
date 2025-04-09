<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AuditAnswer;
use App\Models\DetailAuditAnswer;
use App\Models\DetailSignatureAuditAnswer;
use App\Models\Lantai;
use Illuminate\Http\Request;

class AuditOfficeSteercoController extends Controller
{
    public function showLantai()
    {
        $lantai = Lantai::all();
        return view('steerco.audit-office.index', compact('lantai'));
    }

    public function showArea($id)
    {
        $lantaiId = $id;
        $area = Area::with('lantai:id,lantai', 'karyawans:emp_id,emp_name')->where('lantai_id', $lantaiId)->get();
        return view('steerco.audit-office.area', compact('area'));
    }

    public function showAuditForm($id)
    {
        $areaId = $id;
        $lantaiId = Area::find($areaId)->lantai_id;
        $audit_form = AuditAnswer::with('area:id,area,lantai_id', 'auditor:id,name')->where('area_id', $areaId)->orderBy('created_at', 'desc')->get();
        return view('steerco.audit-office.audit-form', compact('audit_form', 'lantaiId'));
    }

    public function getAuditAnswer($id)
    {
        $auditAnswerId = $id;
        $data = DetailAuditAnswer::with([
            'variabel.temaForm.form',
            'detailAuditeeAnswer.userAuditee',
            'detailFotoAuditAnswer'
        ])->where('audit_answer_id', $auditAnswerId)->get();

        if ($data->isEmpty() || $data->contains(fn($detail) => $detail->audit_answer_id != $auditAnswerId)) {
            //
        }

        $formattedData = $data->map(function ($detail) {
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
                        'auditee' => $auditee->userAuditee ? $auditee->userAuditee->emp_name : $auditee->auditee_name,
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

        $signatures = DetailSignatureAuditAnswer::where('audit_answer_id', $auditAnswerId)->first();
        $auditAnswer = AuditAnswer::where('id', $auditAnswerId)->first();
        $grade = $this->getGrade($auditAnswerId);

        return view('steerco.audit-office.detail.index', compact('formattedData', 'signatures', 'auditAnswer', 'grade'));
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
