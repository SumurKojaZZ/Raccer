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
        if (Schema::hasTable('tutor') && !Schema::hasColumn('tutor', 'status')) {
            Schema::table('tutor', function (Blueprint $table) {
                $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif')->after('spesialisasi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tutor') && Schema::hasColumn('tutor', 'status')) {
            Schema::table('tutor', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};

