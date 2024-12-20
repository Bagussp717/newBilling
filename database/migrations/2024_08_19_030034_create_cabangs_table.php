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
        Schema::create('cabangs', function (Blueprint $table) {
            $table->id('kd_cabang'); 
            $table->string('nm_cabang');
            $table->text('alamat_cabang');
            $table->text('username_mikrotik');
            $table->text('ip_mikrotik');
            $table->text('password_mikrotik');
            $table->bigInteger('kd_isp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabangs');
    }
};
