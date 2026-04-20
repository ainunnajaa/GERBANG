<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_contents', function (Blueprint $table) {
            $table->string('privacy_status', 20)->default('unlisted')->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('school_contents', function (Blueprint $table) {
            $table->dropColumn('privacy_status');
        });
    }
};
