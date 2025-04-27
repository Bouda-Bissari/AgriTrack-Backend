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
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|required|in:Semis,Arrosage,Fertilisation,Recolte,Traitement',
            'isDone' => 'nullable|boolean',
            'productQuantity' => 'required|numeric|min:0',
            'description' => 'required|string',
            'land_id' => 'required|exists:lands,id',
        ];
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

            'productQuantity.required' => 'La quantité de produit est requise.',
            'productQuantity.numeric' => 'La quantité de produit doit être un nombre.',
            'productQuantity.min' => 'La quantité de produit ne peut pas être négative.',

            'description.required' => 'La description est requise.',
            'description.string' => 'La description doit être une chaîne de caractères.',

            'land_id.required' => 'Le champ terrain (land_id) est requis.',
            'land_id.exists' => 'Le terrain sélectionné est invalide ou n\'existe pas.',
        ];
    }
}
