<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('presensi_settings', 'jam_masuk_start_sabtu')) {
                $table->time('jam_masuk_start_sabtu')->nullable()->after('jam_pulang_end');
            }
            if (!Schema::hasColumn('presensi_settings', 'jam_masuk_end_sabtu')) {
                $table->time('jam_masuk_end_sabtu')->nullable()->after('jam_masuk_start_sabtu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            if (Schema::hasColumn('presensi_settings', 'jam_masuk_start_sabtu')) {
                $table->dropColumn('jam_masuk_start_sabtu');
            }
            if (Schema::hasColumn('presensi_settings', 'jam_masuk_end_sabtu')) {
                $table->dropColumn('jam_masuk_end_sabtu');
            }
        });
    }
};
