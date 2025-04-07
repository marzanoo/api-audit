<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaPicHistory extends Model
{
    use HasFactory;
    protected $table = 'area_pic_histories';
    protected $primaryKey = 'id';
    protected $fillable = ['area_id', 'emp_id', 'bulan', 'tahun'];
}
