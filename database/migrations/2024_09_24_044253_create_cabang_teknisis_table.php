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
        Schema::create('cabang_teknisis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kd_teknisi');
            $table->unsignedBigInteger('kd_cabang');

            // Foreign key constraints
            $table->foreign('kd_teknisi')->references('kd_teknisi')->on('teknisis')->onDelete('cascade');
            $table->foreign('kd_cabang')->references('kd_cabang')->on('cabangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang_teknisis');
    }
};
