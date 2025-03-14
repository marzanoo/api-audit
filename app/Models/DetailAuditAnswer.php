<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAuditAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_answer_id',
        'variabel_form_id',
        'score',
    ];

    public function audit_answer()
    {
        return $this->belongsTo(AuditAnswer::class, 'audit_answer_id', 'id');
    }

    public function variabel()
    {
        return $this->belongsTo(VariabelForm::class, 'variabel_form_id', 'id');
    }

    public function detailAuditeeAnswer()
    {
        return $this->hasMany(DetailAuditeeAnswer::class, 'detail_audit_answer_id', 'id');
    }

    public function detailFotoAuditAnswer()
    {
        return $this->hasMany(DetailFotoAuditAnswer::class, 'detail_audit_answer_id', 'id');
    }
}
