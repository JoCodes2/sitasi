<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengajuan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'detail_pengajuan';

    protected $fillable = [
        'id',
        'pengajuan_id',
        'pilihan_judul',
        'judul',
        'deskripsi_singkat',
        'topik_id',
        'file_review_jurnal_1',
        'file_review_jurnal_2',
        'file_review_jurnal_3',
        'created_at',
        'updated_at'
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function topik(): BelongsTo
    {
        return $this->belongsTo(TopikPenelitian::class, 'topik_id');
    }
}
