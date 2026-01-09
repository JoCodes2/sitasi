<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kepakaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kepakaran';

    protected $fillable = [
        'id',
        'dosen_id',
        'topik_id',
        'persentase',
        'created_at',
        'updated_at'
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function topik(): BelongsTo
    {
        return $this->belongsTo(TopikPenelitian::class, 'topik_id');
    }
}
