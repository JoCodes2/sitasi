<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'dosen';

    protected $fillable = [
        'id',
        'nidn',
        'nama_lengkap',
        'gelar',
        'jabatan_fungsional',
        'jabatan_struktural',
        'kuota_max',
        'no_hp',
        'email',
        'alamat',
        'created_at',
        'updated_at'
    ];

    public function kepakaran(): HasMany
    {
        return $this->hasMany(Kepakaran::class, 'dosen_id');
    }

    public function bimbingan1(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'dosen_pembimbing_1_id');
    }

    public function bimbingan2(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'dosen_pembimbing_2_id');
    }
}
