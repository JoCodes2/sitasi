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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mahasiswa_id')->constrained('mahasiswa');
            $table->foreignUuid('gelombang_id')->constrained('gelombang');

            $table->enum('harapan_judul', ['1', '2', '3']);
            $table->text('alasan_prioritas');
            $table->integer('indeks_judul_acc')->nullable();
            $table->foreignUuid('dosen_pembimbing_1_id')->nullable()->constrained('dosen');
            $table->foreignUuid('dosen_pembimbing_2_id')->nullable()->constrained('dosen');

            $table->enum('status_pengajuan', ['pending', 'approved', 'fixing', 'published'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->dateTime('tgl_plotting')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
