<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemaForm extends Model
{
    use HasFactory;

    protected $table = 'tema_forms';

    protected $fillable = [
        'form_id',
        'tema',
    ];

    public function form() {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    public function variabel() {
        return $this->hasMany(VariabelForm::class, 'tema_form_id', 'id');
    }
}
