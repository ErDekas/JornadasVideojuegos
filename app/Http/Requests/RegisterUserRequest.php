<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cambiar si hay lógica de permisos
    }

    public function rules()
    {
        // dd($this->all());        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'tipo_inscripcion' => 'required|in:virtual,presential,student', // Estos valores deben coincidir con registration_type
            'certificado_alumno' => 'required_if:tipo_inscripcion,free|boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'tipo_inscripcion.in' => 'Elige una inscripción válida.',
            'certificado_alumno.required_if' => 'Debes certificar que eres alumno para inscripción gratuita.'
        ];
    }
}
