<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraEntradaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipo_entrada' => 'required|in:presencial,virtual,gratuita',
            'pago' => 'required_if:tipo_entrada,presencial,virtual'
        ];
    }

    public function messages()
    {
        return [
            'pago.required_if' => 'Debes realizar el pago si tu inscripción no es gratuita.'
        ];
    }
}
