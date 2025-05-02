<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddInterventionRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:Semis,Arrosage,Fertilisation,Recolte,Traitement',
            'isDone' => 'nullable|boolean',
            'description' => 'required|string',
            'land_id' => 'required|exists:lands,id',
        ];

        if (in_array($this->input('type'), ['Semis', 'Fertilisation', 'Traitement'])) {
            $rules['quantity'] = 'required|numeric|min:0';
            $rules['unit'] = 'required|string|max:50';
            $rules['product_name'] = 'required|string|max:255';
        } else {
            $rules['quantity'] = 'nullable|numeric|min:0';
            $rules['unit'] = 'nullable|string|max:50';
            $rules['product_name'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est requis.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',

            'type.required' => 'Le type d\'intervention est requis.',
            'type.in' => 'Le type doit être l\'un des suivants : Semis, Arrosage, Fertilisation, Recolte ou Traitement.',

            'isDone.boolean' => 'Le champ "isDone" doit être un booléen.',

            'quantity.required' => 'La quantité est requise pour ce type d\'intervention.',
            'quantity.numeric' => 'La quantité doit être un nombre.',
            'quantity.min' => 'La quantité ne peut pas être négative.',

            'unit.required' => 'L\'unité est requise pour ce type d\'intervention.',
            'unit.string' => 'L\'unité doit être une chaîne de caractères.',
            'unit.max' => 'L\'unité ne doit pas dépasser 50 caractères.',

            'product_name.required' => 'Le nom du produit est requis pour ce type d\'intervention.',
            'product_name.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'product_name.max' => 'Le nom du produit ne doit pas dépasser 255 caractères.',

            'description.required' => 'La description est requise.',
            'description.string' => 'La description doit être une chaîne de caractères.',

            'land_id.required' => 'Le terrain est requis.',
            'land_id.exists' => 'Le terrain sélectionné est invalide ou n\'existe pas.',
        ];
    }
}
