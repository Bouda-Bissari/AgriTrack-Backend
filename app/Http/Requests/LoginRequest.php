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
            'email'                 => 'required|string|email',
            'password'              => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => "L'adresse e-mail est obligatoire.",
            'email.string'      => "L'adresse e-mail doit être une chaîne de caractères.",
            'email.email'       => "L'adresse e-mail doit être valide.",

            'password.required' => "Le mot de passe est obligatoire.",
            'password.string'   => "Le mot de passe doit être une chaîne de caractères.",

          
        ];
    }
}
