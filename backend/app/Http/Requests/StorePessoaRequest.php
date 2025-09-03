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
            'pessoa.nome' => 'required|string|max:255',
            'pessoa.email' => 'required|string|email|max:255',
            'pessoa.role' => [
                'required',
                'string',
                Rule::in(['client', 'teacher', 'student']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'pessoa.nome.required' => 'O nome é obrigatório.',
            'pessoa.nome.string' => 'O nome deve ser uma string.',
            'pessoa.nome.max' => 'O nome não pode ter mais de :max caracteres.',

            'pessoa.email.required' => 'O e-mail é obrigatório.',
            'pessoa.email.string' => 'O e-mail deve ser uma string.',
            'pessoa.email.email' => 'O e-mail informado não é válido.',
            'pessoa.email.max' => 'O e-mail não pode ter mais de :max caracteres.',

            'pessoa.role.required' => 'O cargo (role) é obrigatório.',
            'pessoa.role.string' => 'O cargo (role) deve ser uma string.',
            'pessoa.role.in' => 'O cargo (role) deve ser um dos seguintes: client, teacher ou student.',
        ];
    }
}
