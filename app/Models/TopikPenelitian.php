<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopikPenelitian extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'topik_penelitian';

    protected $fillable = [
        'id',
        'nama_topik',
        'created_at',
        'updated_at'
    ];

    public function kepakaran(): HasMany
    {
        return $this->hasMany(Kepakaran::class, 'topik_id');
    }

    public function detail_pengajuan(): HasMany
    {
        return $this->hasMany(DetailPengajuan::class, 'topik_id');
    }
}
