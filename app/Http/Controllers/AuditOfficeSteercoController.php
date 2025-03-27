<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AuditAnswer;
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
        $audit_form = AuditAnswer::with('area:id,area,lantai_id', 'auditor:id,name')->where('area_id', $areaId)->get();
        return view('steerco.audit-office.audit-form', compact('audit_form', 'lantaiId'));
    }
}
