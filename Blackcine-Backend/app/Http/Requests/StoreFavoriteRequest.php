<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:film,series,article,emission',
            'item_id' => 'required|integer|min:1',
            'name' => 'nullable|string|max:255',
            'poster' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Le type est requis.',
            'type.in' => 'Le type doit être : film, series, article ou emission.',
            'item_id.required' => "L'identifiant de l'élément est requis.",
            'item_id.integer' => "L'identifiant doit être un nombre entier.",
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
