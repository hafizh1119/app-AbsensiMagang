<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    /** 
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'nip',
        'email',
        'password',
        'role',
        'unit_id',
        'jabatan',
        'tempat_lahir',
        'tanggal_lahir',
        'kewarganegaraan',
        'status_kewarganegaraan',
        'no_hp',
        'alamat',
        'foto',
        'status',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'last_login' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * User belongs to one Unit.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * User has many Absensi.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function pengajuanIzin()
    {
        return $this->hasMany(PengajuanIzin::class);
    }

    public function pengajuanIzinDisetujui()
    {
        return $this->hasMany(PengajuanIzin::class, 'disetujui_oleh');
    }
}