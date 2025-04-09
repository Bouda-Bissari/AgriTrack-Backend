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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'date' => 'required|date',
            'city' => 'required|string|max:50',
            'cultureType' => 'required|string|max:150',
            'area' => 'required|numeric|min:0',
            'ownershipdoc' => 'required|file|mimes:pdf',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'statut' => 'required|in:En culture,Récolte,En jachère',
        ];
    }
}
