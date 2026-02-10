<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->string('qr_text')->nullable()->after('jam_pulang_end');
        });
    }

    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->dropColumn('qr_text');
        });
    }
};
