<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('period_type', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->json('active_days');
            $table->boolean('is_active')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_periods');
    }
};