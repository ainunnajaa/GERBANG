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
            $table->longText('school_profile')->nullable()->after('welcome_message');
            $table->string('social_facebook_url')->nullable()->after('contact_opening_hours');
            $table->string('social_instagram_url')->nullable()->after('social_facebook_url');
            $table->string('social_youtube_url')->nullable()->after('social_instagram_url');
            $table->string('contact_maps_url')->nullable()->after('social_youtube_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'school_profile',
                'social_facebook_url',
                'social_instagram_url',
                'social_youtube_url',
                'contact_maps_url',
            ]);
        });
    }
};
