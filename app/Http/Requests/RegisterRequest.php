<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'firstName'                  => 'required|string|max:255',
            'lastName'                   => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:4',
            // 'role'                 => 'required|in:landOwner,worker',
        ];
    }

    public function messages()
    {
        return [
            

            'firstName.required'    => "Le prénom est obligatoire.",
            'firstName.string'      => "Le prénom doit être une chaîne de caractères.",
            'firstName.max'         => "Le prénom ne doit pas dépasser 255 caractères.",

            'lastName.required'     => "Le nom de famille est obligatoire.",
            'lastName.string'       => "Le nom de famille doit être une chaîne de caractères.",
            'lastName.max'          => "Le nom de famille ne doit pas dépasser 255 caractères.",


            
            'email.required'        => "L'adresse e-mail est obligatoire.",
            'email.string'          => "L'adresse e-mail doit être une chaîne de caractères.",
            'email.email'           => "L'adresse e-mail doit être valide.",
            'email.max'             => "L'adresse e-mail ne doit pas dépasser 255 caractères.",
            'email.unique'          => "Cette adresse e-mail est déjà utilisée.",
           

            'password.required'     => "Le mot de passe est obligatoire.",
            'password.string'       => "Le mot de passe doit être une chaîne de caractères.",
            'password.min'          => "Le mot de passe doit comporter au moins 6 caractères.",
            'password.confirmed'    => "La confirmation du mot de passe ne correspond pas.",
        ];
    }
}
