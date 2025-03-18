<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSignatureAuditAnswer extends Model
{
    use HasFactory;
    protected $table = 'detail_signature_audit_answers';
    protected $fillable = [
        'audit_answer_id',
        'auditor_signature',
        'auditee_signature',
        'facilitator_signature'
    ];


    public function auditAnswer()
    {
        return $this->belongsTo(AuditAnswer::class, 'audit_answer_id', 'id');
    }
}
