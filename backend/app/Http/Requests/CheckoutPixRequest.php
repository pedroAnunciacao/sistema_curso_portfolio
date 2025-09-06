<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPixRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');
        $validator = [

            "mercado_pago" => [
                'checkoutPix.amount' => 'required|numeric|min:0.01',
                'checkoutPix.description' => 'required|string|max:255',
                'checkoutPix.payer.email' => 'required|email',
                'checkoutPix.payer.first_name' => 'nullable|string|max:255',
                'checkoutPix.payer.last_name' => 'nullable|string|max:255',
                'checkoutPix.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'checkoutPix.payer.identification.number' => 'required|string',
                'checkoutPix.expiration_minutes' => 'nullable|integer|min:1',
                'checkoutPix.model_type' => 'required',
                'checkoutPix.model_id' => 'required',
            ],

            "stripe" => [
                'checkoutPix.amount' => 'required|numeric|min:1.00',
                'checkoutPix.description' => 'required|string|max:500',
                'checkoutPix.payer.email' => 'required|email',
                'checkoutPix.payer.first_name' => 'required|string|max:255',
                'checkoutPix.payer.last_name' => 'nullable|string|max:255',
                'checkoutPix.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'checkoutPix.payer.identification.number' => 'required|string',
                'checkoutPix.expiration_minutes' => 'nullable|integer|min:1',
                'checkoutPix.model_type' => 'required',
                'checkoutPix.model_id' => 'required',
            ],

            "paypal" => [
                'checkoutPix.amount' => 'required|numeric|min:5.00',
                'checkoutPix.description' => 'required|string|max:400',
                'checkoutPix.payer.email' => 'required|email',
                'checkoutPix.payer.first_name' => 'required|string|max:255',
                'checkoutPix.payer.last_name' => 'nullable|string|max:255',
                'checkoutPix.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'checkoutPix.payer.identification.number' => 'required|string',
                'checkoutPix.expiration_minutes' => 'nullable|integer|min:1',
                'checkoutPix.model_type' => 'required',
                'checkoutPix.model_id' => 'required',
            ],

        ];
        return $validator[$activeGateway];
    }


    public function attributes(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $attributes = [
            "mercado_pago" => [
                'checkoutPix.amount' => 'valor do pagamento',
                'checkoutPix.description' => 'descrição do pagamento',
                'checkoutPix.payer.email' => 'e-mail do pagador',
                'checkoutPix.payer.first_name' => 'nome do pagador',
                'checkoutPix.payer.last_name' => 'sobrenome do pagador',
                'checkoutPix.payer.identification.type' => 'tipo de documento',
                'checkoutPix.payer.identification.number' => 'número do documento',
                'checkoutPix.expiration_minutes' => 'expiração do pagamento',
                'checkoutPix.model_type' => 'tipo de modelo',
                'checkoutPix.model_id' => 'id do modelo',
            ],
            "stripe" => [
                'checkoutPix.amount' => 'valor do pagamento',
                'checkoutPix.description' => 'descrição do pagamento',
                'checkoutPix.payer.email' => 'e-mail do pagador',
                'checkoutPix.payer.first_name' => 'nome do pagador',
                'checkoutPix.payer.last_name' => 'sobrenome do pagador',
                'checkoutPix.payer.identification.type' => 'tipo de documento',
                'checkoutPix.payer.identification.number' => 'número do documento',
                'checkoutPix.expiration_minutes' => 'expiração do pagamento',
                'checkoutPix.model_type' => 'tipo de modelo',
                'checkoutPix.model_id' => 'id do modelo',
            ],
            "paypal" => [
                'checkoutPix.amount' => 'valor do pagamento',
                'checkoutPix.description' => 'descrição do pagamento',
                'checkoutPix.payer.email' => 'e-mail do pagador',
                'checkoutPix.payer.first_name' => 'nome do pagador',
                'checkoutPix.payer.last_name' => 'sobrenome do pagador',
                'checkoutPix.payer.identification.type' => 'tipo de documento',
                'checkoutPix.payer.identification.number' => 'número do documento',
                'checkoutPix.expiration_minutes' => 'expiração do pagamento',
                'checkoutPix.model_type' => 'tipo de modelo',
                'checkoutPix.model_id' => 'id do modelo',
            ],
        ];

        return $attributes[$activeGateway] ?? [];
    }

    public function messages(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $messages = [
            "mercado_pago" => [
                'checkoutPix.amount.required' => 'O :attribute é obrigatório.',
                'checkoutPix.amount.numeric'  => 'O :attribute deve ser um número.',
                'checkoutPix.amount.min'      => 'O :attribute deve ser no mínimo :min.',
                'checkoutPix.description.required' => 'A :attribute é obrigatória.',
                'checkoutPix.description.string'   => 'A :attribute deve ser um texto.',
                'checkoutPix.description.max'      => 'A :attribute deve ter no máximo :max caracteres.',
                'checkoutPix.payer.email.required' => 'O :attribute é obrigatório.',
                'checkoutPix.payer.email.email'    => 'O :attribute deve ser um e-mail válido.',
                'checkoutPix.payer.first_name.string' => 'O :attribute deve ser um texto.',
                'checkoutPix.payer.first_name.max'    => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutPix.payer.last_name.string'  => 'O :attribute deve ser um texto.',
                'checkoutPix.payer.last_name.max'     => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutPix.payer.identification.type.required' => 'O :attribute é obrigatório.',
                'checkoutPix.payer.identification.type.in'       => 'O :attribute deve ser CPF ou CNPJ.',
                'checkoutPix.payer.identification.number.required' => 'O :attribute é obrigatório.',
                'checkoutPix.payer.identification.number.string'   => 'O :attribute deve ser um texto.',
                'checkoutPix.expiration_minutes.integer' => 'O :attribute deve ser um número inteiro.',
                'checkoutPix.expiration_minutes.min'     => 'O :attribute deve ser no mínimo :min.',
                'checkoutPix.model_type.required' => 'O :attribute é obrigatório.',
                'checkoutPix.model_id.required'   => 'O :attribute é obrigatório.',
            ],
            "stripe" => [
                'checkoutPix.amount.required' => 'O :attribute é obrigatório.',
                'checkoutPix.amount.numeric'  => 'O :attribute deve ser um número.',
                'checkoutPix.amount.min'      => 'O :attribute deve ser no mínimo :min.',
                'checkoutPix.description.required' => 'A :attribute é obrigatória.',
                'checkoutPix.description.string'   => 'A :attribute deve ser um texto.',
                'checkoutPix.description.max'      => 'A :attribute deve ter no máximo :max caracteres.',
                'checkoutPix.payer.email.required' => 'O :attribute é obrigatório.',
                'checkoutPix.payer.email.email'    => 'O :attribute deve ser um e-mail válido.',
                'checkoutPix.payer.first_name.string' => 'O :attribute deve ser um texto.',
                'checkoutPix.payer.first_name.max'    => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutPix.payer.last_name.string'  => 'O :attribute deve ser um texto.',
                'checkoutPix.payer.last_name.max'     => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutPix.payer.identification.type.required' => 'O :attribute é obrigatório.',
                'checkoutPix.payer.identification.type.in'       => 'O :attribute deve ser CPF ou CNPJ.',
                'checkoutPix.payer.identification.number.required' => 'O :attribute é obrigatório.',
                'checkoutPix.payer.identification.number.string'   => 'O :attribute deve ser um texto.',
                'checkoutPix.expiration_minutes.integer' => 'O :attribute deve ser um número inteiro.',
                'checkoutPix.expiration_minutes.min'     => 'O :attribute deve ser no mínimo :min.',
                'checkoutPix.model_type.required' => 'O :attribute é obrigatório.',
                'checkoutPix.model_id.required'   => 'O :attribute é obrigatório.',
            ],
            "paypal" => [
                'checkoutPix.amount.required' => 'O :attribute é obrigatório.',
                'checkoutPix.amount.numeric'  => 'O :attribute deve ser um número.',
                'checkoutPix.amount.min'      => 'O :attribute deve ser no mínimo :min.',
                'checkoutPix.description.required' => 'A :attribute é obrigatória.',
                'checkoutPix.description.string'   => 'A :attribute deve ser um texto.',
                'checkoutPix.description.max'      => 'A :attribute deve ter no máximo :max caracteres.',
                'checkoutPix.payer.email.required' => 'O :attribute é obrigatório.',
                'checkoutPix.payer.email.email'    => 'O :attribute deve ser um e-mail válido.',
                'checkoutPix.payer.first_name.string' => 'O :attribute deve ser um texto.',
                'checkoutPix.payer.first_name.max'    => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutPix.payer.last_name.string'  => 'O :attribute deve ser um texto.',
                'checkoutPix.payer.last_name.max'     => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutPix.payer.identification.type.required' => 'O :attribute é obrigatório.',
                'checkoutPix.payer.identification.type.in'       => 'O :attribute deve ser CPF ou CNPJ.',
                'checkoutPix.payer.identification.number.required' => 'O :attribute é obrigatório.',
                'checkoutPix.payer.identification.number.string'   => 'O :attribute deve ser um texto.',
                'checkoutPix.expiration_minutes.integer' => 'O :attribute deve ser um número inteiro.',
                'checkoutPix.expiration_minutes.min'     => 'O :attribute deve ser no mínimo :min.',
                'checkoutPix.model_type.required' => 'O :attribute é obrigatório.',
                'checkoutPix.model_id.required'   => 'O :attribute é obrigatório.',
            ],
        ];

        return $messages[$activeGateway] ?? [];
    }
}
