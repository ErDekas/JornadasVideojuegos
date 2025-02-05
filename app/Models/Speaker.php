<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    protected $fillable = [
        'name',
        'photo',
        'expertise',
        'social_links',
    ];

    protected $casts = [
        'expertise' => 'array',
        'social_links' => 'array',
    ];
}