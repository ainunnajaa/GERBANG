<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_settings', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk_start');
            $table->time('jam_masuk_end');
            $table->time('jam_pulang_start');
            $table->time('jam_pulang_end');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_settings');
    }
};
