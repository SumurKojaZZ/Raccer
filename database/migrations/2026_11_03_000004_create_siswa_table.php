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
        Schema::create('siswa', function (Blueprint $table) {
            $table->increments('id_siswa');
            $table->unsignedInteger('id_keluarga');
            $table->unsignedInteger('id_pengguna');
            $table->string('nama_lengkap', 150);
            $table->string('nama_panggilan', 50);
            $table->enum('jenjang', ['TK', 'SD']);
            $table->string('kelas', 20);
            $table->date('tanggal_lahir');
            $table->enum('status_siswa', ['Aktif', 'Non_Aktif_Alumni'])->default('Aktif');
            $table->dateTime('dibuat_pada')->useCurrent();

            $table->unique('id_pengguna', 'uq_siswa_pengguna');
            $table->index('id_keluarga', 'idx_siswa_keluarga');

            $table->foreign('id_keluarga', 'fk_siswa_keluarga')
                ->references('id_keluarga')->on('akun_keluarga')
                ->onDelete('cascade');

            $table->foreign('id_pengguna', 'fk_siswa_pengguna')
                ->references('id_pengguna')->on('pengguna')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};

