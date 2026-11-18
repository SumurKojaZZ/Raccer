<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Backlog B-14 / T-04: Menambahkan hash PIN pada tabel siswa (Muhammad Fadhil Swarigading)
     */
    public function up(): void
    {
        if (Schema::hasTable('siswa')) {
            Schema::table('siswa', function (Blueprint $table) {
                if (!Schema::hasColumn('siswa', 'pin_hash')) {
                    $table->string('pin_hash', 255)->nullable()->after('status_siswa');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('siswa') && Schema::hasColumn('siswa', 'pin_hash')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropColumn('pin_hash');
            });
        }
    }
};
