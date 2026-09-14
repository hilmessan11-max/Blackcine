<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => "L'adresse email est requise.",
            'email.email' => "L'adresse email n'est pas valide.",
            'email.max' => "L'adresse email ne peut pas dépasser 255 caractères.",
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
