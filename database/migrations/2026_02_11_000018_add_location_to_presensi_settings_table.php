<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('qr_text');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->integer('radius_meter')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius_meter']);
        });
    }
};
