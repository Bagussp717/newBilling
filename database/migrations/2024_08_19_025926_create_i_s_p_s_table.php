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
        Schema::create('i_s_p_s', function (Blueprint $table) {
            $table->id('kd_isp');
            $table->unsignedBigInteger('kd_user');
            $table->string('nm_isp');
            $table->string('nm_brand');
            $table->text('alamat');
            $table->string('no_telp');
            $table->string('logo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_s_p_s');
    }
};
