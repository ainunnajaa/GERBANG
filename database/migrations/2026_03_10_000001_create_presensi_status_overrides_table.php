<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('presensi_status_overrides', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->onDelete('cascade');
			$table->date('tanggal');
			$table->string('status', 1);
			$table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
			$table->timestamps();

			$table->unique(['user_id', 'tanggal']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('presensi_status_overrides');
	}
};