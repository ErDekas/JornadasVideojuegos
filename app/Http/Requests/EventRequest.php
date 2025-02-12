<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:conferencia,taller',
            'ponente_id' => 'required|exists:ponentes,id',
            'fecha' => 'required|date|in:jueves,viernes',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ];
    }

    public function messages()
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'tipo.in' => 'El tipo de evento debe ser conferencia o taller.',
            'fecha.in' => 'Solo se pueden programar eventos los jueves y viernes.',
            'hora_fin.after' => 'La hora de finalización debe ser posterior a la de inicio.'
        ];
    }
}

