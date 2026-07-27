<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    /**
     * Nama tabel
     */
    protected $table = 'absensi';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * Mass Assignment
     */
    protected $fillable = [
        'user_id',
        'lokasi_absensi_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'latitude',
        'longitude',
        'foto_masuk',
        'foto_pulang',
        'status',
        'keterangan',
    ];

    /**
     * Attribute Casting
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jam_masuk' => 'datetime:H:i:s',
            'jam_pulang' => 'datetime:H:i:s',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Absensi dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Absensi dilakukan pada satu Lokasi Absensi.
     */
    public function lokasiAbsensi()
    {
        return $this->belongsTo(LokasiAbsensi::class);
    }
}