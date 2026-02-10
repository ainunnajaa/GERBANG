<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
		'tanggal',
		'jam_masuk',
		'jam_pulang',
	];

	protected $casts = [
		'tanggal' => 'date',
		'jam_masuk' => 'datetime:H:i',
		'jam_pulang' => 'datetime:H:i',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

