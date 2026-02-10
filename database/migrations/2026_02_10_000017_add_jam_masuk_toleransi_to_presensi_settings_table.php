<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->time('jam_masuk_toleransi')->nullable()->after('jam_masuk_end');
        });
    }

    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->dropColumn('jam_masuk_toleransi');
        });
    }
};
