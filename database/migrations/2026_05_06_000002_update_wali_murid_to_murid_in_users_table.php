<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::table('users')->where('role', 'wali_murid')->update(['role' => 'murid']);

        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['mysql', 'pgsql'], true)) {
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'murid'");
        }
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'murid')->update(['role' => 'wali_murid']);

        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['mysql', 'pgsql'], true)) {
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'wali_murid'");
        }
    }
};
