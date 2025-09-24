<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutCardRequest extends FormRequest
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
                'checkoutCard.amount' => 'required|numeric|min:0.01',
                'checkoutCard.description' => 'required|string|max:255',
                'checkoutCard.payer.email' => 'required|email',
                'checkoutCard.payer.first_name' => 'nullable|string|max:255',
                'checkoutCard.payer.last_name' => 'nullable|string|max:255',
                'checkoutCard.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'checkoutCard.payer.identification.number' => 'required|string',
                'checkoutCard.card.id' => 'required|string',
                'checkoutCard.card.card_number' => 'required|string',
                'checkoutCard.installments' => 'nullable|integer|min:1',
                'checkoutCard.model_type' => 'required',
                'checkoutCard.model_id' => 'required',
            ],

            "stripe" => [
                'checkoutCard.amount' => 'required|numeric|min:1.00',
                'checkoutCard.description' => 'required|string|max:500',
                'checkoutCard.payer.email' => 'required|email',
                'checkoutCard.payer.first_name' => 'required|string|max:255',
                'checkoutCard.payer.last_name' => 'nullable|string|max:255',
                'checkoutCard.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'checkoutCard.payer.identification.number' => 'required|string',
                'checkoutCard.card.id' => 'required|string',
                'checkoutCard.card.card_number' => 'required|string',
                'checkoutCard.installments' => 'nullable|integer|min:1',
                'checkoutCard.model_type' => 'required',
                'checkoutCard.model_id' => 'required',
            ],

            "paypal" => [
                'checkoutCard.amount' => 'required|numeric|min:5.00',
                'checkoutCard.description' => 'required|string|max:400',
                'checkoutCard.payer.email' => 'required|email',
                'checkoutCard.payer.first_name' => 'required|string|max:255',
                'checkoutCard.payer.last_name' => 'nullable|string|max:255',
                'checkoutCard.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'checkoutCard.payer.identification.number' => 'required|string',
                'checkoutCard.card.id' => 'required|string',
                'checkoutCard.card.card_number' => 'required|string',
                'checkoutCard.installments' => 'nullable|integer|min:1',
                'checkoutCard.model_type' => 'required',
                'checkoutCard.model_id' => 'required',
            ],
        ];

        return $validator[$activeGateway];
    }

    public function attributes(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $attributes = [
            "mercado_pago" => [
                'checkoutCard.amount' => 'valor do pagamento',
                'checkoutCard.description' => 'descrição do pagamento',
                'checkoutCard.payer.email' => 'e-mail do pagador',
                'checkoutCard.payer.first_name' => 'nome do pagador',
                'checkoutCard.payer.last_name' => 'sobrenome do pagador',
                'checkoutCard.payer.identification.type' => 'tipo de documento',
                'checkoutCard.payer.identification.number' => 'número do documento',
                'checkoutCard.card.id' => 'ID do cartão',
                'checkoutCard.card.card_number' => 'número do cartão',
                'checkoutCard.installments' => 'parcelas',
                'checkoutCard.model_type' => 'tipo de modelo',
                'checkoutCard.model_id' => 'id do modelo',
            ],

            "stripe" => [
                'checkoutCard.amount' => 'valor do pagamento',
                'checkoutCard.description' => 'descrição do pagamento',
                'checkoutCard.payer.email' => 'e-mail do pagador',
                'checkoutCard.payer.first_name' => 'nome do pagador',
                'checkoutCard.payer.last_name' => 'sobrenome do pagador',
                'checkoutCard.payer.identification.type' => 'tipo de documento',
                'checkoutCard.payer.identification.number' => 'número do documento',
                'checkoutCard.card.id' => 'ID do cartão',
                'checkoutCard.card.card_number' => 'número do cartão',
                'checkoutCard.installments' => 'parcelas',
                'checkoutCard.model_type' => 'tipo de modelo',
                'checkoutCard.model_id' => 'id do modelo',
            ],

            "paypal" => [
                'checkoutCard.amount' => 'valor do pagamento',
                'checkoutCard.description' => 'descrição do pagamento',
                'checkoutCard.payer.email' => 'e-mail do pagador',
                'checkoutCard.payer.first_name' => 'nome do pagador',
                'checkoutCard.payer.last_name' => 'sobrenome do pagador',
                'checkoutCard.payer.identification.type' => 'tipo de documento',
                'checkoutCard.payer.identification.number' => 'número do documento',
                'checkoutCard.card.id' => 'ID do cartão',
                'checkoutCard.card.card_number' => 'número do cartão',
                'checkoutCard.installments' => 'parcelas',
                'checkoutCard.model_type' => 'tipo de modelo',
                'checkoutCard.model_id' => 'id do modelo',
            ],
        ];

        return $attributes[$activeGateway] ?? [];
    }

    public function messages(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $messages = [
            "mercado_pago" => [
                'checkoutCard.amount.required' => 'O :attribute é obrigatório.',
                'checkoutCard.amount.numeric' => 'O :attribute deve ser um número.',
                'checkoutCard.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'checkoutCard.description.required' => 'O :attribute é obrigatório.',
                'checkoutCard.payer.email.required' => 'O :attribute é obrigatório.',
                'checkoutCard.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'checkoutCard.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
                'checkoutCard.card.id.required' => 'O :attribute é obrigatório.',
                'checkoutCard.card.card_number.required' => 'O :attribute é obrigatório.',
                'checkoutCard.installments.integer' => 'O :attribute deve ser um número inteiro.',
                'checkoutCard.installments.min' => 'O :attribute deve ser no mínimo :min.',
            ],

            "stripe" => [
                'checkoutCard.amount.required' => 'O :attribute é obrigatório.',
                'checkoutCard.amount.numeric' => 'O :attribute deve ser um número.',
                'checkoutCard.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'checkoutCard.description.required' => 'O :attribute é obrigatório.',
                'checkoutCard.description.max' => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutCard.payer.email.required' => 'O :attribute é obrigatório.',
                'checkoutCard.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'checkoutCard.payer.first_name.required' => 'O :attribute é obrigatório.',
                'checkoutCard.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
                'checkoutCard.card.id.required' => 'O :attribute é obrigatório.',
                'checkoutCard.card.card_number.required' => 'O :attribute é obrigatório.',
                'checkoutCard.installments.integer' => 'O :attribute deve ser um número inteiro.',
                'checkoutCard.installments.min' => 'O :attribute deve ser no mínimo :min.',
            ],

            "paypal" => [
                'checkoutCard.amount.required' => 'O :attribute é obrigatório.',
                'checkoutCard.amount.numeric' => 'O :attribute deve ser um número.',
                'checkoutCard.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'checkoutCard.description.required' => 'O :attribute é obrigatório.',
                'checkoutCard.description.max' => 'O :attribute deve ter no máximo :max caracteres.',
                'checkoutCard.payer.email.required' => 'O :attribute é obrigatório.',
                'checkoutCard.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'checkoutCard.payer.first_name.required' => 'O :attribute é obrigatório.',
                'checkoutCard.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
                'checkoutCard.card.id.required' => 'O :attribute é obrigatório.',
                'checkoutCard.card.card_number.required' => 'O :attribute é obrigatório.',
                'checkoutCard.installments.integer' => 'O :attribute deve ser um número inteiro.',
                'checkoutCard.installments.min' => 'O :attribute deve ser no mínimo :min.',
            ],
        ];

        return $messages[$activeGateway] ?? [];
    }
}
