<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->string('qr_template_path')->nullable()->after('radius_meter');
            $table->decimal('qr_template_x', 5, 2)->nullable()->after('qr_template_path');
            $table->decimal('qr_template_y', 5, 2)->nullable()->after('qr_template_x');
            $table->decimal('qr_template_size', 5, 2)->nullable()->after('qr_template_y');
        });
    }

    public function down(): void
    {
        Schema::table('presensi_settings', function (Blueprint $table) {
            $table->dropColumn(['qr_template_path', 'qr_template_x', 'qr_template_y', 'qr_template_size']);
        });
    }
};
