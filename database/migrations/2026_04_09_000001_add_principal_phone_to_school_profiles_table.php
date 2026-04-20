<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('school_profiles', 'principal_phone')) {
                $table->string('principal_phone')->nullable()->after('principal_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('school_profiles', 'principal_phone')) {
                $table->dropColumn('principal_phone');
            }
        });
    }
};
