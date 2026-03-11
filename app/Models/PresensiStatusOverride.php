<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiStatusOverride extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
		'tanggal',
		'status',
		'updated_by',
	];

	protected $casts = [
		'tanggal' => 'date',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function updatedBy()
	{
		return $this->belongsTo(User::class, 'updated_by');
	}
}