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
                if (!Schema::hasColumn('jadwal', 'link_kelas')) {
                    $table->string('link_kelas', 2048)->nullable()->after('lokasi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('jadwal') && Schema::hasColumn('jadwal', 'link_kelas')) {
            Schema::table('jadwal', function (Blueprint $table) {
                $table->dropColumn('link_kelas');
            });
        }
    }
};
