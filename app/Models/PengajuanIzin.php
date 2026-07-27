<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzin extends Model
{
    use HasFactory;
    protected $table = 'pengajuan_izin';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'jenis_izin_id',
        'tanggal_pengajuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi',
        'alasan',
        'lampiran',
        'catatan',
        'status',
        'disetujui_oleh',
        'tanggal_persetujuan',
    ];
    protected function casts(): array
    {
        return [
            'tanggal_pengajuan'   => 'date',
            'tanggal_mulai'       => 'date',
            'tanggal_selesai'     => 'date',
            'tanggal_persetujuan' => 'datetime',
            'durasi'              => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Pengajuan izin dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pengajuan izin memiliki satu jenis izin.
     */
    public function jenisIzin()
    {
        return $this->belongsTo(JenisIzin::class);
    }

    /**
     * User yang menyetujui pengajuan izin.
     */
    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}