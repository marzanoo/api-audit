<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAuditeeAnswer extends Model
{
    use HasFactory;

    protected $table = 'detail_auditee_answers';

    protected $fillable = [
        'detail_audit_answer_id',
        'auditee',
    ];

    public function detailAuditAnswer()
    {
        return $this->belongsTo(DetailAuditAnswer::class, 'detail_audit_answer_id', 'id');
    }
}
