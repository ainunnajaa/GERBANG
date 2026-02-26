<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->string('status')->nullable()->after('jam_pulang');
            $table->text('keterangan')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn(['status', 'keterangan']);
        });
    }
};
