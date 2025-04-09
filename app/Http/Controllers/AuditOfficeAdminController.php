<?php

namespace App\Http\Controllers;

use App\Exports\AuditAnswerExport;
use App\Models\Area;
use App\Models\AuditAnswer;
use App\Models\DetailAuditAnswer;
use App\Models\DetailSignatureAuditAnswer;
use App\Models\Lantai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AuditOfficeAdminController extends Controller
{
    public function showLantai()
    {
        $lantai = Lantai::all();
        return view('admin.audit-office.index', compact('lantai'));
    }

    public function showArea($id)
    {
        $lantaiId = $id;
        $area = Area::with('lantai:id,lantai', 'karyawans:emp_id,emp_name')->where('lantai_id', $lantaiId)->get();
        return view('admin.audit-office.area', compact('area'));
    }

    public function showAuditForm($id)
    {
        $areaId = $id;
        $lantaiId = Area::find($areaId)->lantai_id;
        $audit_form = AuditAnswer::with('area:id,area,lantai_id', 'auditor:id,name')->where('area_id', $areaId)->orderBy('created_at', 'desc')->get();
        return view('admin.audit-office.audit-form', compact('audit_form', 'lantaiId'));
    }

    public function showAuditAnswer($id)
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

        return view('admin.audit-office.detail.index', compact('formattedData', 'signatures', 'auditAnswer', 'grade'));
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

    public function previewExcel($id)
    {
        $auditAnswerId = $id;
        $data = DetailAuditAnswer::with([
            'variabel.temaForm.form',
            'detailAuditeeAnswer.userAuditee',
            'detailFotoAuditAnswer'
        ])->where('audit_answer_id', $auditAnswerId)->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('audit_office_error', 'Data tidak ditemukan');
        }

        $formattedData = $this->formatAuditData($data);
        $auditAnswer = AuditAnswer::where('id', $auditAnswerId)->first();
        $grade = $this->getGrade($auditAnswerId);

        return view('admin.audit-office.detail.preview-excel', compact('formattedData', 'auditAnswer', 'grade', 'id'));
    }

    public function downloadExcel($id)
    {
        $auditAnswerId = $id;
        $data = DetailAuditAnswer::with([
            'variabel.temaForm.form',
            'detailAuditeeAnswer.userAuditee',
            'detailFotoAuditAnswer'
        ])->where('audit_answer_id', $auditAnswerId)->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('audit_office_error', 'Data tidak ditemukan');
        }

        $formattedData = $this->formatAuditData($data);
        $auditAnswer = AuditAnswer::where('id', $auditAnswerId)->first();
        $grade = $this->getGrade($auditAnswerId);

        $fileName = 'Audit_Report_' . $auditAnswer->area_id . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new AuditAnswerExport($formattedData, $auditAnswer, $grade), $fileName);
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
    }

    public function downloadPdf($id)
    {
        $auditAnswerId = $id;
        $data = DetailAuditAnswer::with([
            'variabel.temaForm.form',
            'detailAuditeeAnswer.userAuditee',
            'detailFotoAuditAnswer'
        ])->where('audit_answer_id', $auditAnswerId)->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('audit_office_error', 'Data tidak ditemukan');
        }

        $formattedData = $this->formatAuditData($data);
        $auditAnswer = AuditAnswer::where('id', $auditAnswerId)->first();
        $grade = $this->getGrade($auditAnswerId);

        // Install package PDF: composer require barryvdh/laravel-dompdf
        $pdf = Pdf::loadView('admin.audit-office.detail.pdf', compact('formattedData', 'auditAnswer', 'grade'));
        $fileName = 'Audit_Report_' . $auditAnswer->area_id . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }
}
