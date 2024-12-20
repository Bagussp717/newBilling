<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id('kd_tiket');
            $table->unsignedBigInteger('kd_user');
            $table->unsignedBigInteger('kd_pelanggan');
            $table->string('deskripsi_tiket');
            $table->string('foto')->nullable();
            $table->enum('status_tiket', ['Pengajuan', 'Diterima', 'Diproses', 'Selesai'])
                ->default('Pengajuan');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tikets');
    }
}
