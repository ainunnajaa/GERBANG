<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadSetting extends Model
{
    use HasFactory;

    protected $table = 'download_settings';

    protected $fillable = [
        'link_berita',
        'link_gerbang',
        'install_guide_link',
    ];
}
