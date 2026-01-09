<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajuan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pengajuan';

    protected $fillable = [
        'id',
        'mahasiswa_id',
        'gelombang_id',
        'indeks_judul_acc',
        'dosen_pembimbing_1_id',
        'dosen_pembimbing_2_id',
        'status_pengajuan',
        'catatan_admin',
        'tgl_plotting',
        'created_at',
        'updated_at'
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(Gelombang::class, 'gelombang_id');
    }

    public function pembimbing1(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_1_id');
    }

    public function pembimbing2(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_2_id');
    }

    public function detail_pengajuan(): HasMany
    {
        return $this->hasMany(DetailPengajuan::class, 'pengajuan_id');
    }
}
