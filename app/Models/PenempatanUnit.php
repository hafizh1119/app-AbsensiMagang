<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanUnit extends Model
{
    use HasFactory;

    protected $table = 'penempatan_unit';

    protected $fillable = [
        'user_id',
        'jenis_unit_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisUnit()
    {
        return $this->belongsTo(JenisUnit::class);
    }
}