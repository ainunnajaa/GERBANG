<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->time('jam_pulang_start_jumat')->nullable()->after('jam_pulang_end');
            $table->time('jam_pulang_end_jumat')->nullable()->after('jam_pulang_start_jumat');
            $table->time('jam_pulang_start_sabtu')->nullable()->after('jam_pulang_end_jumat');
            $table->time('jam_pulang_end_sabtu')->nullable()->after('jam_pulang_start_sabtu');
        });
    }

    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->dropColumn([
                'jam_pulang_start_jumat',
                'jam_pulang_end_jumat',
                'jam_pulang_start_sabtu',
                'jam_pulang_end_sabtu',
            ]);
        });
    }
};
