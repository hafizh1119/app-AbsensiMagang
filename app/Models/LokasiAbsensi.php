<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiAbsensi extends Model
{
    use HasFactory;
    protected $table = 'lokasi_absensi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_lokasi',
        'alamat',
        'latitude',
        'longitude',
        'radius',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'radius' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Satu lokasi absensi memiliki banyak data absensi.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}