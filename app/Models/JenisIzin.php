<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisIzin extends Model
{
    use HasFactory;
    protected $table = 'jenis_izin';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_izin',
        'deskripsi',
        'status',
    ];

    protected function casts(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Satu jenis izin memiliki banyak pengajuan izin.
     */
    public function pengajuanIzin()
    {
        return $this->hasMany(PengajuanIzin::class);
    }
}