<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'welcome_message',
        'principal_name',
        'principal_photo_path',
        'principal_greeting',
        'vision',
        'mission',
        'contact_address',
        'contact_email',
        'contact_phone',
        'contact_opening_hours',
        'updated_by',
        // Optional: add page title/content into the same model if needed later
        // 'title',
        // 'content',
    ];

    public function programs()
    {
        return $this->hasMany(SchoolProgram::class);
    }

    public function contents()
    {
        return $this->hasMany(SchoolContent::class);
    }

    public function backgrounds()
    {
        return $this->hasMany(SchoolBackground::class);
    }
}
