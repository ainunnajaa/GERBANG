<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'jam_masuk_start',
        'jam_masuk_end',
        'jam_masuk_toleransi',
        'jam_pulang_start',
        'jam_pulang_end',
        'qr_text',
        'latitude',
        'longitude',
        'radius_meter',
    ];
}
