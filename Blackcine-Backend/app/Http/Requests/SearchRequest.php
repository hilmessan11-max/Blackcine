<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => 'required|string|min:2|max:100',
            'type' => 'nullable|string|in:all,films,series,articles,emissions',
        ];
    }

    public function messages(): array
    {
        return [
            'q.required' => 'Le terme de recherche est requis.',
            'q.min' => 'Le terme de recherche doit contenir au moins 2 caractères.',
            'q.max' => 'Le terme de recherche ne peut pas dépasser 100 caractères.',
            'type.in' => 'Le type doit être : all, films, series, articles ou emissions.',
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
