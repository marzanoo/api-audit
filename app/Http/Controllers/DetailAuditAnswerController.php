<?php

namespace App\Http\Controllers;

use App\Models\AuditAnswer;
use App\Models\DetailAuditAnswer;
use App\Models\DetailAuditeeAnswer;
use App\Models\DetailFotoAuditAnswer;
use App\Models\DetailSignatureAuditAnswer;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DetailAuditAnswerController extends Controller
{
    public function showFormAuditDetail($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu');
        }
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
        return view('auditor.form-audit.detail.index', compact('detailAuditAnswer', 'auditAnswerId'));
    }

    public function submitAnswer(Request $request)
    {
        DB::beginTransaction();
        try {
            $auditAnswerId = $request->input('audit_answer_id');

            $totalScore = 0;
            foreach ($request->input('score', []) as $detailAuditAnswerId => $score) {
                $detail = DetailAuditAnswer::findOrFail($detailAuditAnswerId);
                $detail->score = $score;
                $detail->save();

                $totalScore += $score;

                $this->processTertuduh(
                    $detailAuditAnswerId,
                    $request->input('tertuduh_' . $detailAuditAnswerId, []),
                    $request->input('temuan_' . $detailAuditAnswerId, [])
                );

                $this->processImageUploads(
                    $detailAuditAnswerId,
                    $request->file('image_path', [])
                );
            }

            $auditAnswer = AuditAnswer::findOrFail($auditAnswerId);
            $auditAnswer->total_score = $totalScore;
            $auditAnswer->save();

            $this->processSignatures($auditAnswerId, $request);

            DB::commit();
            Log::info('Audit Answer Submitted Successfully', [
                'user_id' => Auth::id(),
                'audit_answer_id' => $auditAnswerId,
                'total_score' => $totalScore
            ]);

            return redirect()->route('dashboard')->with('audit_answer_success', 'Audit berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('detail-audit-answer', $auditAnswerId)->with(['audit_answer_error' => 'Gagal menyimpan audit: ' . $e->getMessage()]);
        }
    }

    private function processTertuduh($detailAuditAnswerId, $tertuduhList, $temuanList)
    {
        // Hapus data tertuduh sebelumnya
        DetailAuditeeAnswer::where('detail_audit_answer_id', $detailAuditAnswerId)->delete();

        // Proses setiap tertuduh
        foreach ($tertuduhList as $index => $tertuduh) {
            if (empty($tertuduh)) continue;

            $temuan = $temuanList[$index] ?? null;

            // Cari karyawan berdasarkan nama
            $employee = Karyawan::where('emp_name', 'like', '%' . $tertuduh . '%')->first();

            DetailAuditeeAnswer::create([
                'detail_audit_answer_id' => $detailAuditAnswerId,
                'auditee' => $employee ? $employee->emp_id : null,
                'auditee_name' => $employee ? null : $tertuduh,
                'temuan' => $temuan
            ]);
        }
    }
    private function processImageUploads($detailAuditAnswerId, $imageFiles)
    {
        if (!isset($imageFiles[$detailAuditAnswerId])) return;

        foreach ($imageFiles[$detailAuditAnswerId] as $imageFile) {
            $fileName = time() . '_' . $imageFile->getClientOriginalName();
            $filePath = $imageFile->storeAs('uploads', $fileName, 'public');

            DetailFotoAuditAnswer::create([
                'detail_audit_answer_id' => $detailAuditAnswerId,
                'image_path' => $filePath
            ]);
        }
    }

    private function processSignatures($auditAnswerId, $request)
    {
        $signatures = [
            'auditor_signature' => $request->file('auditor_signature'),
            'auditee_signature' => $request->file('auditee_signature'),
            'facilitator_signature' => $request->file('facilitator_signature')
        ];

        $signaturePaths = [];

        foreach ($signatures as $type => $signatureFile) {
            if ($signatureFile) {
                $fileName = time() . '_' . $type . '.' . $signatureFile->getExtension();
                $filePath = $signatureFile->storeAs('signatures', $fileName, 'public');
                $signaturePaths[$type] = $filePath;
            }
        }

        // Simpan signature jika ada
        if (!empty($signaturePaths)) {
            DetailSignatureAuditAnswer::create([
                'audit_answer_id' => $auditAnswerId,
                'auditor_signature' => $signaturePaths['auditor_signature'] ?? null,
                'auditee_signature' => $signaturePaths['auditee_signature'] ?? null,
                'facilitator_signature' => $signaturePaths['facilitator_signature'] ?? null
            ]);
        }
    }
}
