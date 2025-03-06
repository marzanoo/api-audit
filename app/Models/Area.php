<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    // protected $guarded = [];

    protected $fillable = [
        'lantai_id',
        'area',
        'pic_area',
    ];

    public function lantai()
    {
        return $this->belongsTo(Lantai::class, 'lantai_id', 'id');
    }

    public function karyawans()
    {
        return $this->belongsTo(Karyawan::class, 'pic_area', 'emp_id');
    }
}
