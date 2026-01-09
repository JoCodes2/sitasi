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
        Schema::create('gelombang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('semester', ['ganjil', 'genap']);
            $table->string('tahun_ajaran');
            $table->integer('gelombang_ke');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gelombang');
    }
};
