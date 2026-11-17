<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Backlog B-11: Memilih peserta jadwal kelas menurut jenis program (Muhammad Fadhil Swarigading)
     */
    public function up(): void
    {
        if (!Schema::hasTable('peserta_jadwal')) {
            Schema::create('peserta_jadwal', function (Blueprint $table) {
                $table->increments('id_peserta_jadwal');
                $table->unsignedBigInteger('id_jadwal')->index();
                $table->unsignedInteger('id_siswa')->index();
                $table->unsignedInteger('id_paket_siswa')->nullable()->index();
                $table->string('status_kehadiran', 50)->default('Terdaftar');
                $table->string('catatan', 255)->nullable();
                $table->timestamps();

                // Mencegah satu siswa didaftarkan ganda pada satu jadwal sesi kelas yang sama
                $table->unique(['id_jadwal', 'id_siswa'], 'uq_jadwal_siswa');

                if (Schema::hasTable('jadwal')) {
                    $table->foreign('id_jadwal', 'fk_peserta_jadwal_jadwal')
                        ->references('id')->on('jadwal')
                        ->onDelete('cascade');
                }

                if (Schema::hasTable('siswa')) {
                    $table->foreign('id_siswa', 'fk_peserta_jadwal_siswa')
                        ->references('id_siswa')->on('siswa')
                        ->onDelete('cascade');
                }

                if (Schema::hasTable('paket_siswa')) {
                    $table->foreign('id_paket_siswa', 'fk_peserta_jadwal_paket')
                        ->references('id_paket_siswa')->on('paket_siswa')
                        ->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_jadwal');
    }
};
