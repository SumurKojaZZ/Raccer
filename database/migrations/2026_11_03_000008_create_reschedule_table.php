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
        Schema::create('reschedule', function (Blueprint $table) {
            $table->id();
            $table->string('nama_siswa');
            $table->string('inisial')->nullable();
            $table->string('kelas');
            $table->string('jadwal_lama');
            $table->string('jadwal_baru');
            $table->string('status')->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reschedule');
    }
};

