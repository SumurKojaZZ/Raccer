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
        if (Schema::hasTable('jadwal')) {
            Schema::table('jadwal', function (Blueprint $table) {
                if (!Schema::hasColumn('jadwal', 'program')) {
                    $table->string('program')->nullable()->after('id');
                }
                if (!Schema::hasColumn('jadwal', 'tutor_pengampu')) {
                    $table->string('tutor_pengampu')->nullable()->after('tutor');
                }
                if (!Schema::hasColumn('jadwal', 'jam_mulai')) {
                    $table->string('jam_mulai')->nullable()->after('tanggal');
                }
                if (!Schema::hasColumn('jadwal', 'jam_selesai')) {
                    $table->string('jam_selesai')->nullable()->after('jam_mulai');
                }
                if (!Schema::hasColumn('jadwal', 'moda')) {
                    $table->string('moda')->default('Offline')->after('waktu_selesai');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('jadwal')) {
            Schema::table('jadwal', function (Blueprint $table) {
                $columns = ['program', 'tutor_pengampu', 'jam_mulai', 'jam_selesai', 'moda'];
                $existing = array_filter($columns, fn ($col) => Schema::hasColumn('jadwal', $col));
                if (!empty($existing)) {
                    $table->dropColumn(array_values($existing));
                }
            });
        }
    }
};

