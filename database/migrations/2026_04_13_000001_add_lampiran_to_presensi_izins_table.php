<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_izins', function (Blueprint $table) {
            $table->string('lampiran_path')->nullable()->after('keterangan');
            $table->string('lampiran_nama')->nullable()->after('lampiran_path');
        });
    }

    public function down(): void
    {
        Schema::table('presensi_izins', function (Blueprint $table) {
            $table->dropColumn(['lampiran_path', 'lampiran_nama']);
        });
    }
};
