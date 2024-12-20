<?php

use App\Models\Pelanggan;
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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id('kd_pelanggan');
            $table->unsignedBigInteger('kd_user');
            $table->date('tgl_pemasangan')->nullable();
            $table->enum('jenis_identitas', ['KTP', 'SIM', 'Paspor'])->nullable();
            $table->string('no_identitas')->nullable();
            $table->text('nm_pelanggan')->nullable();
            $table->text('t_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->unsignedBigInteger('kd_cabang')->nullable();
            $table->unsignedBigInteger('kd_paket')->nullable();
            $table->unsignedBigInteger('kd_loket')->nullable();
            $table->unsignedBigInteger('kd_odp')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('foto_rumah')->nullable();
            $table->string('username_pppoe')->nullable();
            $table->string('password_pppoe')->nullable();
            $table->string('service_pppoe')->nullable();
            $table->string('profile_pppoe')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
