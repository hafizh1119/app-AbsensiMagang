<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $table = 'unit';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_unit',
        'tanggal_bergabung',
        'deskripsi',
        'status',
    ];
    protected function casts(): array
    {
        return [
            'tanggal_bergabung' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Satu unit memiliki banyak user.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}