<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_pengajuan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pengajuan_id')->constrained('pengajuan')->onDelete('cascade');
            $table->enum('pilihan_judul', ['1', '2', '3']);
            $table->string('judul');
            $table->text('deskripsi_singkat');
            $table->foreignUuid('topik_id')->constrained('topik_penelitian');
            $table->string('file_review_jurnal_1');
            $table->string('file_review_jurnal_2');
            $table->string('file_review_jurnal_3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengajuan');
    }
};
