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
        'jam_pulang_start_jumat',
        'jam_pulang_end_jumat',
        'jam_pulang_start_sabtu',
        'jam_pulang_end_sabtu',
        'qr_text',
        'latitude',
        'longitude',
        'radius_meter',
        'qr_template_path',
        'qr_template_x',
        'qr_template_y',
        'qr_template_size',
    ];
}
