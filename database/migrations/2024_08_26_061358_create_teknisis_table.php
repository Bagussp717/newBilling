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
        Schema::create('teknisis', function (Blueprint $table) {
            $table->id('kd_teknisi'); // Auto Increment Primary Key
            $table->unsignedBigInteger('kd_user');
            $table->string('nm_teknisi');
            $table->string('t_lahir');
            $table->date('tgl_lahir');
            $table->date('tgl_aktif');
            $table->string('alamat_teknisi');
            $table->string('no_telp');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teknisis');
    }
};
