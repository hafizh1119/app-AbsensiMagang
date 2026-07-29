<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    protected $table = 'jam_kerja';

    protected $fillable = [
        'hari',
        'jam_masuk',
        'jam_pulang',
        'toleransi_terlambat',
        'status',
    ];

    protected $casts = [
        'jam_masuk' => 'datetime:H:i',
        'jam_pulang' => 'datetime:H:i',
        'status' => 'boolean',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}