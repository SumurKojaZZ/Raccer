<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('paket_siswa')) {
            Schema::create('paket_siswa', function (Blueprint $table) {
                $table->increments('id_paket_siswa');
                $table->unsignedInteger('id_siswa')->index();
                $table->string('program', 150)->default('Junior Robotics');
                $table->string('jenis_paket', 100)->default('Semester');
                $table->string('paket_belajar', 255)->default('Semester • Junior Robotics (12 Sesi)');
                $table->integer('total_sesi')->default(12);
                $table->integer('sesi_terpakai')->default(0);
                $table->string('status_paket', 50)->default('Aktif');
                $table->string('status_pembayaran', 50)->default('Belum Bayar');
                $table->string('metode_pembayaran', 100)->nullable();
                $table->string('catatan', 255)->nullable();
                $table->dateTime('tanggal_aktivasi')->useCurrent();
                $table->timestamps();

                if (Schema::hasTable('siswa')) {
                    $table->foreign('id_siswa')
                        ->references('id_siswa')
                        ->on('siswa')
                        ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_siswa');
    }
};
