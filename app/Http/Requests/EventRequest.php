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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'type' => 'required|in:conference,workshop',
            'date' => 'required|date',
            'start_time' => 'required', // Cambiar a solo formato de hora
            'end_time' => 'required', // Cambiar a solo formato de hora
            'max_attendees' => 'required|integer|min:1',
            'location' => ['required', function ($attribute, $value, $fail) {
                // Validación para conferencias
                if ($this->input('type') == 'conference' && $value != 'auditorium') {
                    $fail('Las conferencias deben ser en el auditorio.');
                }
                // Validación para talleres
                if ($this->input('type') == 'workshop' && $value != 'classroom') {
                    $fail('Los talleres deben ser en el aula.');
                }
            }],
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
