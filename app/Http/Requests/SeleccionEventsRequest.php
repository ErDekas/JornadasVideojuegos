<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeleccionEventsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'conferencias' => 'array|max:5',
            'conferencias.*' => 'exists:eventos,id|distinct',
            'talleres' => 'array|max:4',
            'talleres.*' => 'exists:eventos,id|distinct'
        ];
    }

    public function messages()
    {
        return [
            'conferencias.max' => 'Solo puedes seleccionar hasta 5 conferencias.',
            'talleres.max' => 'Solo puedes seleccionar hasta 4 talleres.'
        ];
    }
}

