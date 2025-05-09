<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddLandRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'area' => 'required|numeric|min:0',
            'cultureType' => 'required|string|max:150',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'statut' => 'required|in:En culture,Récolte,En jachère',
            'ownershipdoc' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120', // 5MB max
                function ($attribute, $value, $fail) {
                    if (request()->hasFile('ownershipdoc')) {
                        $filename = request()->file('ownershipdoc')->getClientOriginalName();
                        $exists = \App\Models\Land::where('ownershipdoc', 'like', "%$filename")->exists();

                        if ($exists) {
                            $fail("Un document avec ce nom existe déjà.");
                        }
                    }
                }
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'user_id.required' => 'L\'identifiant de l\'utilisateur est requis.',
            'user_id.exists' => 'L\'utilisateur sélectionné est invalide.',

            'name.required' => 'Le nom du terrain est requis.',
            'name.max' => 'Le nom du terrain ne doit pas dépasser 50 caractères.',

            'city.required' => 'La ville est requise.',
            'city.max' => 'Le nom de la ville ne doit pas dépasser 50 caractères.',

            'area.required' => 'La superficie est requise.',
            'area.numeric' => 'La superficie doit être un nombre.',
            'area.min' => 'La superficie doit être supérieure ou égale à 0.',

            'cultureType.required' => 'Le type de culture est requis.',
            'cultureType.max' => 'Le type de culture ne doit pas dépasser 150 caractères.',

            'latitude.required' => 'La latitude est requise.',
            'latitude.numeric' => 'La latitude doit être un nombre.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',

            'longitude.required' => 'La longitude est requise.',
            'longitude.numeric' => 'La longitude doit être un nombre.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',

            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut doit être : En culture, Récolte ou En jachère.',

            'ownershipdoc.required' => 'Le document de propriété est requis.',
            'ownershipdoc.file' => 'Le document de propriété doit être un fichier.',
            'ownershipdoc.mimes' => 'Le document doit être un fichier PDF.',
            'ownershipdoc.max' => 'Le document ne doit pas dépasser 5 Mo.',
        ];
    }
}
