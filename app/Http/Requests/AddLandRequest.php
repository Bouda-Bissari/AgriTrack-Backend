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
}
