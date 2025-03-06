<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lantai extends Model
{
    use HasFactory;

    // protected $guarded = [];
    protected $table = 'lantais';
    protected $fillable = ['id', 'lantai'];

    public function area()
    {
        return $this->hasMany(Area::class, 'lantai_id', 'id');
    }
}
