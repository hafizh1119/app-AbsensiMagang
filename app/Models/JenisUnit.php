<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisUnit extends Model
{
    protected $table = 'jenis_unit';

    protected $fillable = [
        'nama_unit',
        'deskripsi',
        'status',
    ];

    public function unit()
    {
        return $this->hasMany(Unit::class);
    }
}