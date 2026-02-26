<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_izins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->text('keterangan');
            $table->timestamps();

            $table->unique(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_izins');
    }
};
