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
        Schema::create('tutor', function (Blueprint $table) {
            $table->increments('id_tutor');
            $table->unsignedInteger('id_pengguna');
            $table->string('nama_lengkap', 150);
            $table->string('nama_panggilan', 50);
            $table->string('nomor_whatsapp', 16);
            $table->enum('spesialisasi', ['TK', 'SD']);
            $table->dateTime('dibuat_pada')->useCurrent();

            $table->unique('id_pengguna', 'uq_tutor_pengguna');

            $table->foreign('id_pengguna', 'fk_tutor_pengguna')
                ->references('id_pengguna')->on('pengguna')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor');
    }
};

