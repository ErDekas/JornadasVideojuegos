<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8'
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'Este correo no está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ];
    }
}

