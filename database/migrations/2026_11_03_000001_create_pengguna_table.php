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
        Schema::create('pengguna', function (Blueprint $table) {
            $table->increments('id_pengguna');
            $table->string('username', 100)->unique('uq_pengguna_username');
            $table->string('password_hash', 255);
            $table->enum('role', ['Admin', 'Tutor', 'Orang_Tua', 'Siswa']);
            $table->boolean('status_aktif')->default(true);
            $table->unsignedInteger('jumlah_gagal_login')->default(0);
            $table->dateTime('terkunci_sampai')->nullable();
            $table->dateTime('dibuat_pada')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};

