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
        if (!Schema::hasTable('jadwal')) {
            Schema::create('jadwal', function (Blueprint $table) {
                $table->id();
                $table->string('program')->nullable();
                $table->string('kelas')->nullable();
                $table->string('lokasi')->nullable();
                $table->string('tutor')->nullable();
                $table->string('tutor_pengampu')->nullable();
                $table->date('tanggal')->nullable();
                $table->string('jam_mulai')->nullable();
                $table->string('jam_selesai')->nullable();
                $table->string('waktu_mulai')->nullable();
                $table->string('waktu_selesai')->nullable();
                $table->string('moda')->default('Offline');
                $table->string('status')->default('Terjadwal');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
