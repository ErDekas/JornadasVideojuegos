<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Campos que pueden ser asignados masivamente.
     */
    protected $fillable = [
        'registration_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'paypal_order_id'
    ];

    /**
     * Relación con la tabla Registration.
     * Un pago pertenece a una inscripción.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
