<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutBoletoRequest extends FormRequest
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
                'checkoutBoleto.amount' => 'required|numeric|min:0.01',
                'checkoutBoleto.description' => 'required|string|max:255',
                'checkoutBoleto.payer.email' => 'required|email',
                'checkoutBoleto.payer.first_name' => 'nullable|string|max:255',
                'checkoutBoleto.payer.last_name' => 'nullable|string|max:255',
                'checkoutBoleto.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'checkoutBoleto.payer.identification.number' => 'required|string',
                'checkoutBoleto.payer.address.zip_code' => 'nullable|string|max:20',
                'checkoutBoleto.payer.address.street_name' => 'nullable|string|max:255',
                'checkoutBoleto.payer.address.street_number' => 'nullable|string|max:50',
                'checkoutBoleto.payer.address.neighborhood' => 'nullable|string|max:255',
                'checkoutBoleto.payer.address.city' => 'nullable|string|max:255',
                'checkoutBoleto.payer.address.federal_unit' => 'nullable|string|max:2',
                'checkoutBoleto.model_type' => 'required',
                'checkoutBoleto.model_id' => 'required',
            ],

            "stripe" => [
                'checkoutBoleto.amount' => 'required|numeric|min:1.00',
                'checkoutBoleto.description' => 'required|string|max:500',
                'checkoutBoleto.payer.email' => 'required|email',
                'checkoutBoleto.payer.first_name' => 'required|string|max:255',
                'checkoutBoleto.payer.last_name' => 'nullable|string|max:255',
                'checkoutBoleto.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'checkoutBoleto.payer.identification.number' => 'required|string',
                'checkoutBoleto.payer.address.zip_code' => 'nullable|string|max:20',
                'checkoutBoleto.payer.address.street_name' => 'nullable|string|max:255',
                'checkoutBoleto.payer.address.street_number' => 'nullable|string|max:50',
                'checkoutBoleto.payer.address.neighborhood' => 'nullable|string|max:255',
                'checkoutBoleto.payer.address.city' => 'nullable|string|max:255',
                'checkoutBoleto.payer.address.federal_unit' => 'nullable|string|max:2',
                'checkoutBoleto.model_type' => 'required',
                'checkoutBoleto.model_id' => 'required',
            ],

            "paypal" => [
                'checkoutBoleto.amount' => 'required|numeric|min:5.00',
                'checkoutBoleto.description' => 'required|string|max:400',
                'checkoutBoleto.payer.email' => 'required|email',
                'checkoutBoleto.payer.first_name' => 'required|string|max:255',
                'checkoutBoleto.payer.last_name' => 'nullable|string|max:255',
                'checkoutBoleto.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'checkoutBoleto.payer.identification.number' => 'required|string',
                'checkoutBoleto.payer.address.zip_code' => 'nullable|string|max:20',
                'checkoutBoleto.payer.address.street_name' => 'nullable|string|max:255',
                'checkoutBoleto.payer.address.street_number' => 'nullable|string|max:50',
                'checkoutBoleto.payer.address.neighborhood' => 'nullable|string|max:255',
                'checkoutBoleto.payer.address.city' => 'nullable|string|max:255',
                'checkoutBoleto.payer.address.federal_unit' => 'nullable|string|max:2',
                'checkoutBoleto.model_type' => 'required',
                'checkoutBoleto.model_id' => 'required',
            ],
        ];

        return $validator[$activeGateway];
    }

    public function attributes(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $attributes = [
            "mercado_pago" => [
                'checkoutBoleto.amount' => 'valor do pagamento',
                'checkoutBoleto.description' => 'descrição do pagamento',
                'checkoutBoleto.payer.email' => 'e-mail do pagador',
                'checkoutBoleto.payer.first_name' => 'nome do pagador',
                'checkoutBoleto.payer.last_name' => 'sobrenome do pagador',
                'checkoutBoleto.payer.identification.type' => 'tipo de documento',
                'checkoutBoleto.payer.identification.number' => 'número do documento',
                'checkoutBoleto.payer.address.zip_code' => 'CEP',
                'checkoutBoleto.payer.address.street_name' => 'rua',
                'checkoutBoleto.payer.address.street_number' => 'número',
                'checkoutBoleto.payer.address.neighborhood' => 'bairro',
                'checkoutBoleto.payer.address.city' => 'cidade',
                'checkoutBoleto.payer.address.federal_unit' => 'UF',
                'checkoutBoleto.model_type' => 'tipo de modelo',
                'checkoutBoleto.model_id' => 'id do modelo',
            ],

            "stripe" => [
                'checkoutBoleto.amount' => 'valor do pagamento',
                'checkoutBoleto.description' => 'descrição do pagamento',
                'checkoutBoleto.payer.email' => 'e-mail do pagador',
                'checkoutBoleto.payer.first_name' => 'nome do pagador',
                'checkoutBoleto.payer.last_name' => 'sobrenome do pagador',
                'checkoutBoleto.payer.identification.type' => 'tipo de documento',
                'checkoutBoleto.payer.identification.number' => 'número do documento',
                'checkoutBoleto.payer.address.zip_code' => 'CEP',
                'checkoutBoleto.payer.address.street_name' => 'rua',
                'checkoutBoleto.payer.address.street_number' => 'número',
                'checkoutBoleto.payer.address.neighborhood' => 'bairro',
                'checkoutBoleto.payer.address.city' => 'cidade',
                'checkoutBoleto.payer.address.federal_unit' => 'UF',
                'checkoutBoleto.model_type' => 'tipo de modelo',
                'checkoutBoleto.model_id' => 'id do modelo',
            ],

            "paypal" => [
                'checkoutBoleto.amount' => 'valor do pagamento',
                'checkoutBoleto.description' => 'descrição do pagamento',
                'checkoutBoleto.payer.email' => 'e-mail do pagador',
                'checkoutBoleto.payer.first_name' => 'nome do pagador',
                'checkoutBoleto.payer.last_name' => 'sobrenome do pagador',
                'checkoutBoleto.payer.identification.type' => 'tipo de documento',
                'checkoutBoleto.payer.identification.number' => 'número do documento',
                'checkoutBoleto.payer.address.zip_code' => 'CEP',
                'checkoutBoleto.payer.address.street_name' => 'rua',
                'checkoutBoleto.payer.address.street_number' => 'número',
                'checkoutBoleto.payer.address.neighborhood' => 'bairro',
                'checkoutBoleto.payer.address.city' => 'cidade',
                'checkoutBoleto.payer.address.federal_unit' => 'UF',
                'checkoutBoleto.model_type' => 'tipo de modelo',
                'checkoutBoleto.model_id' => 'id do modelo',
            ],
        ];

        return $attributes[$activeGateway] ?? [];
    }

    public function messages(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $messages = [
            "mercado_pago" => [
                'checkoutBoleto.amount.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.amount.numeric' => 'O :attribute deve ser um número.',
                'checkoutBoleto.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'checkoutBoleto.description.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.payer.email.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'checkoutBoleto.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
            ],

            "stripe" => [
                'checkoutBoleto.amount.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.amount.numeric' => 'O :attribute deve ser um número.',
                'checkoutBoleto.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'checkoutBoleto.description.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.description.max' => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutBoleto.payer.email.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'checkoutBoleto.payer.first_name.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
            ],

            "paypal" => [
                'checkoutBoleto.amount.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.amount.numeric' => 'O :attribute deve ser um número.',
                'checkoutBoleto.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'checkoutBoleto.description.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.description.max' => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutBoleto.payer.email.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'checkoutBoleto.payer.first_name.required' => 'O :attribute é obrigatório.',
                'checkoutBoleto.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
            ],
        ];

        return $messages[$activeGateway] ?? [];
    }
}
