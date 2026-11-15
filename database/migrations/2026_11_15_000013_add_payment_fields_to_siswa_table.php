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
                if (!Schema::hasColumn('siswa', 'status_pembayaran')) {
                    $table->string('status_pembayaran', 50)->default('Lunas')->after('status_paket');
                }
                if (!Schema::hasColumn('siswa', 'program')) {
                    $table->string('program', 100)->nullable()->default('Junior Robotics')->after('paket_belajar');
                }
                if (!Schema::hasColumn('siswa', 'jenis_paket')) {
                    $table->string('jenis_paket', 50)->nullable()->default('Semester')->after('program');
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
                $columns = ['status_pembayaran', 'program', 'jenis_paket'];
                $existing = array_filter($columns, fn ($col) => Schema::hasColumn('siswa', $col));
                if (!empty($existing)) {
                    $table->dropColumn(array_values($existing));
                }
            });
        }
    }
};

