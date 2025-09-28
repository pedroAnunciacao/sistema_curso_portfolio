<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCardPaymentRequest extends FormRequest
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
                'paymentCard.amount' => 'required|numeric|min:0.01',
                'paymentCard.description' => 'required|string|max:255',
                'paymentCard.payer.email' => 'required|email',
                'paymentCard.payer.first_name' => 'nullable|string|max:255',
                'paymentCard.payer.last_name' => 'nullable|string|max:255',
                'paymentCard.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'paymentCard.payer.identification.number' => 'required|string',
                'paymentCard.card.id' => 'required|string',
                'paymentCard.card.card_number' => 'required|string',
                'paymentCard.installments' => 'nullable|integer|min:1',
                'paymentCard.model_type' => 'required',
                'paymentCard.model_id' => 'required',
            ],

            "stripe" => [
                'paymentCard.amount' => 'required|numeric|min:1.00',
                'paymentCard.description' => 'required|string|max:500',
                'paymentCard.payer.email' => 'required|email',
                'paymentCard.payer.first_name' => 'required|string|max:255',
                'paymentCard.payer.last_name' => 'nullable|string|max:255',
                'paymentCard.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'paymentCard.payer.identification.number' => 'required|string',
                'paymentCard.card.id' => 'required|string',
                'paymentCard.card.card_number' => 'required|string',
                'paymentCard.installments' => 'nullable|integer|min:1',
                'paymentCard.model_type' => 'required',
                'paymentCard.model_id' => 'required',
            ],

            "paypal" => [
                'paymentCard.amount' => 'required|numeric|min:5.00',
                'paymentCard.description' => 'required|string|max:400',
                'paymentCard.payer.email' => 'required|email',
                'paymentCard.payer.first_name' => 'required|string|max:255',
                'paymentCard.payer.last_name' => 'nullable|string|max:255',
                'paymentCard.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'paymentCard.payer.identification.number' => 'required|string',
                'paymentCard.card.id' => 'required|string',
                'paymentCard.card.card_number' => 'required|string',
                'paymentCard.installments' => 'nullable|integer|min:1',
                'paymentCard.model_type' => 'required',
                'paymentCard.model_id' => 'required',
            ],
        ];


        $modelType = $this->input('paymentCard.model_type');
 
        if ($modelType == 'courses') {
            $validator[$activeGateway]['paymentCard.model_id'] = 'exists:courses,id';
        }
        
        return $validator[$activeGateway];
    }

    public function attributes(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $attributes = [
            "mercado_pago" => [
                'paymentCard.amount' => 'valor do pagamento',
                'paymentCard.description' => 'descrição do pagamento',
                'paymentCard.payer.email' => 'e-mail do pagador',
                'paymentCard.payer.first_name' => 'nome do pagador',
                'paymentCard.payer.last_name' => 'sobrenome do pagador',
                'paymentCard.payer.identification.type' => 'tipo de documento',
                'paymentCard.payer.identification.number' => 'número do documento',
                'paymentCard.card.id' => 'ID do cartão',
                'paymentCard.card.card_number' => 'número do cartão',
                'paymentCard.installments' => 'parcelas',
                'paymentCard.model_type' => 'tipo de modelo',
                'paymentCard.model_id' => 'id do modelo',
            ],

            "stripe" => [
                'paymentCard.amount' => 'valor do pagamento',
                'paymentCard.description' => 'descrição do pagamento',
                'paymentCard.payer.email' => 'e-mail do pagador',
                'paymentCard.payer.first_name' => 'nome do pagador',
                'paymentCard.payer.last_name' => 'sobrenome do pagador',
                'paymentCard.payer.identification.type' => 'tipo de documento',
                'paymentCard.payer.identification.number' => 'número do documento',
                'paymentCard.card.id' => 'ID do cartão',
                'paymentCard.card.card_number' => 'número do cartão',
                'paymentCard.installments' => 'parcelas',
                'paymentCard.model_type' => 'tipo de modelo',
                'paymentCard.model_id' => 'id do modelo',
            ],

            "paypal" => [
                'paymentCard.amount' => 'valor do pagamento',
                'paymentCard.description' => 'descrição do pagamento',
                'paymentCard.payer.email' => 'e-mail do pagador',
                'paymentCard.payer.first_name' => 'nome do pagador',
                'paymentCard.payer.last_name' => 'sobrenome do pagador',
                'paymentCard.payer.identification.type' => 'tipo de documento',
                'paymentCard.payer.identification.number' => 'número do documento',
                'paymentCard.card.id' => 'ID do cartão',
                'paymentCard.card.card_number' => 'número do cartão',
                'paymentCard.installments' => 'parcelas',
                'paymentCard.model_type' => 'tipo de modelo',
                'paymentCard.model_id' => 'id do modelo',
            ],
        ];

        return $attributes[$activeGateway] ?? [];
    }

    public function messages(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $messages = [
            "mercado_pago" => [
                'paymentCard.amount.required' => 'O :attribute é obrigatório.',
                'paymentCard.amount.numeric' => 'O :attribute deve ser um número.',
                'paymentCard.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'paymentCard.description.required' => 'O :attribute é obrigatório.',
                'paymentCard.payer.email.required' => 'O :attribute é obrigatório.',
                'paymentCard.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'paymentCard.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
                'paymentCard.card.id.required' => 'O :attribute é obrigatório.',
                'paymentCard.card.card_number.required' => 'O :attribute é obrigatório.',
                'paymentCard.installments.integer' => 'O :attribute deve ser um número inteiro.',
                'paymentCard.installments.min' => 'O :attribute deve ser no mínimo :min.',
            ],

            "stripe" => [
                'paymentCard.amount.required' => 'O :attribute é obrigatório.',
                'paymentCard.amount.numeric' => 'O :attribute deve ser um número.',
                'paymentCard.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'paymentCard.description.required' => 'O :attribute é obrigatório.',
                'paymentCard.description.max' => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentCard.payer.email.required' => 'O :attribute é obrigatório.',
                'paymentCard.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'paymentCard.payer.first_name.required' => 'O :attribute é obrigatório.',
                'paymentCard.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
                'paymentCard.card.id.required' => 'O :attribute é obrigatório.',
                'paymentCard.card.card_number.required' => 'O :attribute é obrigatório.',
                'paymentCard.installments.integer' => 'O :attribute deve ser um número inteiro.',
                'paymentCard.installments.min' => 'O :attribute deve ser no mínimo :min.',
            ],

            "paypal" => [
                'paymentCard.amount.required' => 'O :attribute é obrigatório.',
                'paymentCard.amount.numeric' => 'O :attribute deve ser um número.',
                'paymentCard.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'paymentCard.description.required' => 'O :attribute é obrigatório.',
                'paymentCard.description.max' => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentCard.payer.email.required' => 'O :attribute é obrigatório.',
                'paymentCard.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'paymentCard.payer.first_name.required' => 'O :attribute é obrigatório.',
                'paymentCard.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
                'paymentCard.card.id.required' => 'O :attribute é obrigatório.',
                'paymentCard.card.card_number.required' => 'O :attribute é obrigatório.',
                'paymentCard.installments.integer' => 'O :attribute deve ser um número inteiro.',
                'paymentCard.installments.min' => 'O :attribute deve ser no mínimo :min.',
            ],
        ];

        return $messages[$activeGateway] ?? [];
    }
}
