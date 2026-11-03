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
        Schema::create('akun_keluarga', function (Blueprint $table) {
            $table->increments('id_keluarga');
            $table->string('nama_keluarga', 150);
            $table->text('alamat_domisili')->comment('Jalan, nomor rumah, RT/RW saja. Daerah di kolom kelurahan, kecamatan, kota_kabupaten');
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota_kabupaten', 100)->nullable();
            $table->dateTime('dibuat_pada')->useCurrent();

            $table->index('kota_kabupaten', 'idx_keluarga_kota');
            $table->index('kecamatan', 'idx_keluarga_kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_keluarga');
    }
};

