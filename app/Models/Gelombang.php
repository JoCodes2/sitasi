<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gelombang extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'gelombang';

    protected $fillable = [
        'id',
        'semester',
        'tahun_ajaran',
        'gelombang_ke',
        'tgl_mulai',
        'tgl_selesai',
        'is_aktif',
        'created_at',
        'updated_at'
    ];

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'gelombang_id');
    }
}
