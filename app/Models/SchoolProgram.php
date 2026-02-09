<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_profile_id',
        'title',
        'description',
        'icon',
        'order',
    ];

    public function profile()
    {
        return $this->belongsTo(SchoolProfile::class, 'school_profile_id');
    }
}
