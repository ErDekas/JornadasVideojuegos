<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type', // 'conference' or 'workshop'
        'date',
        'start_time',
        'end_time',
        'capacity',
        'available_spots',
        'location', // 'auditorium' or 'classroom'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}