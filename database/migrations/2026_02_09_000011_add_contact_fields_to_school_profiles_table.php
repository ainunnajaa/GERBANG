<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->string('contact_address')->nullable()->after('mission');
            $table->string('contact_email')->nullable()->after('contact_address');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->string('contact_opening_hours')->nullable()->after('contact_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'contact_address',
                'contact_email',
                'contact_phone',
                'contact_opening_hours',
            ]);
        });
    }
};
