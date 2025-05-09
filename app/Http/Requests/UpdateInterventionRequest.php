<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterventionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:Semis,Arrosage,Fertilisation,Recolte,Traitement',
            'description' => 'sometimes|string',

            'quantity' => 'nullable|numeric|min:0',        // Ajouté
            'unit' => 'nullable|string|max:50',            // Ajouté
            'product_name' => 'nullable|string|max:255',   // Ajouté

            'isDone' => 'sometimes|boolean', 
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',

            'type.in' => 'Le type doit être l\'un des suivants : Semis, Arrosage, Fertilisation, Recolte ou Traitement.',

            'description.string' => 'La description doit être une chaîne de caractères.',

            'quantity.numeric' => 'La quantité doit être un nombre.',
            'quantity.min' => 'La quantité ne peut pas être négative.',

            'unit.string' => 'L\'unité doit être une chaîne de caractères.',
            'unit.max' => 'L\'unité ne doit pas dépasser 50 caractères.',

            'product_name.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'product_name.max' => 'Le nom du produit ne doit pas dépasser 255 caractères.',

            'isDone.boolean' => 'Le champ "isDone" doit être un booléen.',
        ];
    }
}
