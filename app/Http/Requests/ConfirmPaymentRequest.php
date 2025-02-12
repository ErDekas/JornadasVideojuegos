<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'monto' => 'required|numeric|min:1',
            'paypal_id' => 'required|string|unique:pagos,paypal_id'
        ];
    }

    public function messages()
    {
        return [
            'monto.min' => 'El monto debe ser mayor a 0.',
            'paypal_id.unique' => 'Este pago ya ha sido registrado anteriormente.'
        ];
    }
}

