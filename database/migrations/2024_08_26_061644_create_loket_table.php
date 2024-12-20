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
        Schema::create('lokets', function (Blueprint $table) {
            $table->id('kd_loket'); 
            $table->unsignedBigInteger('kd_user');
            $table->unsignedBigInteger('kd_cabang');
            $table->string('nm_loket');
            $table->string('alamat_loket');
            $table->enum('jenis_komisi', ['fixed', 'dynamic']);
            $table->integer('jml_komisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokets');
    }
};
