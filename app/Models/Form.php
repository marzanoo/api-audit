<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $table = 'forms';

    protected $fillable = [
        'kategori',
        'deskripsi'
    ];

    public function temaForm()
    {
        return $this->hasMany(TemaForm::class, 'form_id', 'id');
    }
}
