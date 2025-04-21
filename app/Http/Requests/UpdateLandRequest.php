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
}
