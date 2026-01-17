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
        Schema::create('dosen', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('nidn', 20)->unique();
            $table->string('nama_lengkap');
            $table->string('gelar');
            $table->string('jabatan_fungsional')->nullable();
            $table->string('jabatan_struktural')->nullable();
            $table->integer('kuota_max')->default(5);
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->text('alamat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
