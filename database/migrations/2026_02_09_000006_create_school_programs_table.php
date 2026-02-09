<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_profile_id')->constrained('school_profiles')->onDelete('cascade');
            $table->string('title');
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_programs');
    }
};
