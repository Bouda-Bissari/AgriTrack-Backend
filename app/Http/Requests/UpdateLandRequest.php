<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandRequest extends FormRequest
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
            'name' => 'sometimes|string|max:50',
            'city' => 'sometimes|string|max:50',
            'area' => 'sometimes|numeric|min:0',
            'cultureType' => 'sometimes|string|max:150',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'statut' => 'sometimes|in:En culture,Récolte,En jachère',
            'ownershipdoc' => [
                'sometimes',
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
            'name.string' => 'Le nom du terrain doit être une chaîne de caractères.',
            'name.max' => 'Le nom du terrain ne doit pas dépasser 50 caractères.',

            'city.string' => 'La ville doit être une chaîne de caractères.',
            'city.max' => 'La ville ne doit pas dépasser 50 caractères.',

            'area.numeric' => 'La superficie doit être un nombre.',
            'area.min' => 'La superficie doit être supérieure ou égale à 0.',

            'cultureType.string' => 'Le type de culture doit être une chaîne de caractères.',
            'cultureType.max' => 'Le type de culture ne doit pas dépasser 150 caractères.',

            'latitude.numeric' => 'La latitude doit être un nombre.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',

            'longitude.numeric' => 'La longitude doit être un nombre.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',

            'statut.in' => 'Le statut doit être : En culture, Récolte ou En jachère.',

            'ownershipdoc.file' => 'Le document de propriété doit être un fichier.',
            'ownershipdoc.mimes' => 'Le document doit être au format PDF.',
            'ownershipdoc.max' => 'Le document ne doit pas dépasser 5 Mo.',
        ];
    }
}
