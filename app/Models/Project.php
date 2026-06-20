<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'role',
        'tech_stack',
        'features',
        'github_link',
        'live_link',
        'image_url',
        'image_alt',
        'status',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'features' => 'array',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];
}
