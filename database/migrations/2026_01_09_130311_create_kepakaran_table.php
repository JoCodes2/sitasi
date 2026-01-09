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
        Schema::create('kepakaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->foreignUuid('topik_id')->constrained('topik_penelitian')->onDelete('cascade');
            $table->integer('persentase'); // 0-100
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepakaran');
    }
};
