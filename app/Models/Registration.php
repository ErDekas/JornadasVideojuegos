<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id',
        'registration_type',
        'payment_status',
        'payment_id',
        'ticket_code',
        'total_amount',
    ];

    protected $casts = [
        'payment_status' => 'boolean',
    ];
}