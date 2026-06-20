<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'rating',
        'text',
        'social_link',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'float',
        'is_approved' => 'boolean',
    ];
}

