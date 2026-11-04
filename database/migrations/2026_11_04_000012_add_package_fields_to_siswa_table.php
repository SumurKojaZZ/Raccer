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
        if (Schema::hasTable('siswa')) {
            Schema::table('siswa', function (Blueprint $table) {
                if (!Schema::hasColumn('siswa', 'nama')) {
                    $table->string('nama')->nullable()->after('nama_panggilan');
                }
                if (!Schema::hasColumn('siswa', 'nama_wali')) {
                    $table->string('nama_wali')->nullable()->after('nama');
                }
                if (!Schema::hasColumn('siswa', 'paket_belajar')) {
                    $table->string('paket_belajar')->nullable()->default('Gold · 8 sesi')->after('status_siswa');
                }
                if (!Schema::hasColumn('siswa', 'total_sesi')) {
                    $table->integer('total_sesi')->default(8)->after('paket_belajar');
                }
                if (!Schema::hasColumn('siswa', 'sesi_terpakai')) {
                    $table->integer('sesi_terpakai')->default(0)->after('total_sesi');
                }
                if (!Schema::hasColumn('siswa', 'status_paket')) {
                    $table->string('status_paket')->default('Aktif')->after('sesi_terpakai');
                }
                if (!Schema::hasColumn('siswa', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('siswa')) {
            Schema::table('siswa', function (Blueprint $table) {
                $columns = ['nama', 'nama_wali', 'paket_belajar', 'total_sesi', 'sesi_terpakai', 'status_paket', 'created_at', 'updated_at'];
                $existing = array_filter($columns, fn ($col) => Schema::hasColumn('siswa', $col));
                if (!empty($existing)) {
                    $table->dropColumn(array_values($existing));
                }
            });
        }
    }
};
