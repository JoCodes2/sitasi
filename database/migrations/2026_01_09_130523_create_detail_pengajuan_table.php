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
            $table->text('latar_belakang');
            $table->foreignUuid('topik_id')->constrained('topik_penelitian');
            $table->enum('status_judul', ['pending', 'approved', 'rejected', 'confirmation'])->default('pending');
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
