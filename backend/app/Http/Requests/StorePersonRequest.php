<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePessoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'person.nome' => 'required|string|max:255',
            'person.email' => 'required|string|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'person.nome.required' => 'O nome é obrigatório.',
            'person.nome.string' => 'O nome deve ser uma string.',
            'person.nome.max' => 'O nome não pode ter mais de :max caracteres.',

            'person.email.required' => 'O e-mail é obrigatório.',
            'person.email.string' => 'O e-mail deve ser uma string.',
            'person.email.email' => 'O e-mail informado não é válido.',
            'person.email.max' => 'O e-mail não pode ter mais de :max caracteres.',

        ];
    }
}
