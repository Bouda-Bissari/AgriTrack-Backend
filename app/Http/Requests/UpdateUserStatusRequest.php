<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_blocked' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'is_blocked.required' => 'Le statut de blocage est requis.',
            'is_blocked.boolean' => 'Le statut de blocage doit être un booléen.',
        ];
    }
}
