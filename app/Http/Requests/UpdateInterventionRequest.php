<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterventionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:Semis,Arrosage,Fertilisation,Recolte,Traitement',
            'isDone' => 'sometimes|boolean',
            'productQuantity' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string',
        ];
    }
    public function messages(): array
    {
        return [
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',

            'type.in' => 'Le type doit être l\'un des suivants : Semis, Arrosage, Fertilisation, Recolte ou Traitement.',

            'isDone.boolean' => 'Le champ "isDone" doit être un booléen.',

            'productQuantity.numeric' => 'La quantité de produit doit être un nombre.',
            'productQuantity.min' => 'La quantité de produit ne peut pas être négative.',

            'description.string' => 'La description doit être une chaîne de caractères.',
        ];
    }
}
