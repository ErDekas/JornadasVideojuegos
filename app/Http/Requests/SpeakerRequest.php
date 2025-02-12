<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpeakerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
            'experiencia' => 'required|array',
            'experiencia.*' => 'string',
            'redes_sociales' => 'nullable|url'
        ];
    }

    public function messages()
    {
        return [
            'foto.image' => 'El archivo debe ser una imagen.',
            'redes_sociales.url' => 'El enlace de redes sociales debe ser una URL válida.'
        ];
    }
}
