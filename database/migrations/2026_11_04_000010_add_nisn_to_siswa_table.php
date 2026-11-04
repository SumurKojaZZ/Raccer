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
        if (Schema::hasTable('siswa') && !Schema::hasColumn('siswa', 'nisn')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('nisn', 20)->nullable()->unique('uq_siswa_nisn')->after('id_pengguna');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('siswa') && Schema::hasColumn('siswa', 'nisn')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropUnique('uq_siswa_nisn');
                $table->dropColumn('nisn');
            });
        }
    }
};

