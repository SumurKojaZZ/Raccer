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
        if (!Schema::hasTable('orang_tua')) {
            Schema::create('orang_tua', function (Blueprint $table) {
                $table->increments('id_orang_tua');
                $table->unsignedInteger('id_keluarga');
                $table->unsignedInteger('id_pengguna');
                $table->enum('jenis_wali', ['Primary_Parent', 'Secondary_Parent']);
                $table->string('nama_lengkap', 150);
                $table->string('hubungan', 50);
                $table->string('nomor_whatsapp', 16);

                $table->unique('id_pengguna', 'uq_orang_tua_pengguna');
                $table->unique(['id_keluarga', 'jenis_wali'], 'uq_keluarga_jenis_wali');

                $table->foreign('id_keluarga', 'fk_orang_tua_keluarga')
                    ->references('id_keluarga')->on('akun_keluarga')
                    ->onDelete('cascade');

                $table->foreign('id_pengguna', 'fk_orang_tua_pengguna')
                    ->references('id_pengguna')->on('pengguna')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tua');
    }
};
