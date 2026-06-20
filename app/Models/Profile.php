<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'name',
        'role',
        'location',
        'email',
        'phone',
        'image_url',
        'cv_url',
        'github_url',
        'linkedin_url',
        'availability',
        'bio',
    ];
}
