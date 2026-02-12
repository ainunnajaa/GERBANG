<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
	use HasFactory;

	protected $fillable = [
		'tanggal_berita',
		'judul',
		'isi',
		'gambar_path',
		'instagram_url',
	];
}
