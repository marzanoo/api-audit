<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'auditor_id',
        'tanggal',
        'area_id',
        'total_score'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id', 'id');
    }

    public function detail_audit_answers()
    {
        return $this->hasMany(DetailAuditAnswer::class, 'audit_answer_id', 'id');
    }

    public function detail_signature_audit_answers()
    {
        return $this->hasMany(DetailSignatureAuditAnswer::class, 'audit_answer_id', 'id');
    }
}
