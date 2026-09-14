<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $this->user()->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.email' => "L'adresse email n'est pas valide.",
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'bio.max' => 'La biographie ne peut pas dépasser 1000 caractères.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $validator->errors(),
        ], 422));
    }
}
