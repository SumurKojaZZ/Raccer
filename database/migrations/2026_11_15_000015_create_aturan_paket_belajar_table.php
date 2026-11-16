<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('aturan_paket_belajar')) {
            Schema::create('aturan_paket_belajar', function (Blueprint $table) {
                $table->increments('id_aturan_paket');
                $table->string('jenis_paket', 50)->unique('uq_aturan_jenis_paket');
                $table->string('nama_aturan', 150);
                $table->integer('min_sesi')->default(1);
                $table->integer('maks_sesi')->default(12);
                $table->integer('sesi_default')->default(12);
                $table->string('deskripsi', 255)->nullable();
                $table->boolean('status_aktif')->default(true);
                $table->timestamps();
            });

            // Seed aturan resmi sesuai instruksi:
            // 1. Semester = 12 sesi (min=12, maks=12)
            // 2. Bulanan = maks 4 sesi (min=1, maks=4)
            DB::table('aturan_paket_belajar')->insert([
                [
                    'jenis_paket' => 'Semester',
                    'nama_aturan' => 'Aturan Paket Semester',
                    'min_sesi' => 12,
                    'maks_sesi' => 12,
                    'sesi_default' => 12,
                    'deskripsi' => 'Paket Semester wajib memiliki tepat 12 sesi pembelajaran.',
                    'status_aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'jenis_paket' => 'Bulanan',
                    'nama_aturan' => 'Aturan Paket Bulanan',
                    'min_sesi' => 1,
                    'maks_sesi' => 4,
                    'sesi_default' => 4,
                    'deskripsi' => 'Paket Bulanan memiliki kuota maksimal 4 sesi pembelajaran per paket.',
                    'status_aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aturan_paket_belajar');
    }
};
