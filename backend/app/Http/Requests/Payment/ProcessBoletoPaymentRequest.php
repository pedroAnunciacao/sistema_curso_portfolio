<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class ProcessBoletoPaymentRequest extends FormRequest
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
                'paymentBoleto.amount' => 'required|numeric|min:0.01',
                'paymentBoleto.description' => 'required|string|max:255',
                'paymentBoleto.payer.email' => 'required|email',
                'paymentBoleto.payer.first_name' => 'nullable|string|max:255',
                'paymentBoleto.payer.last_name' => 'nullable|string|max:255',
                'paymentBoleto.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'paymentBoleto.payer.identification.number' => 'required|string',
                'paymentBoleto.payer.address.zip_code' => 'nullable|string|max:20',
                'paymentBoleto.payer.address.street_name' => 'nullable|string|max:255',
                'paymentBoleto.payer.address.street_number' => 'nullable|string|max:50',
                'paymentBoleto.payer.address.neighborhood' => 'nullable|string|max:255',
                'paymentBoleto.payer.address.city' => 'nullable|string|max:255',
                'paymentBoleto.payer.address.federal_unit' => 'nullable|string|max:2',
                'paymentBoleto.model_type' => 'required',
                'paymentBoleto.model_id' => 'required',
            ],

            "stripe" => [
                'paymentBoleto.amount' => 'required|numeric|min:1.00',
                'paymentBoleto.description' => 'required|string|max:500',
                'paymentBoleto.payer.email' => 'required|email',
                'paymentBoleto.payer.first_name' => 'required|string|max:255',
                'paymentBoleto.payer.last_name' => 'nullable|string|max:255',
                'paymentBoleto.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'paymentBoleto.payer.identification.number' => 'required|string',
                'paymentBoleto.payer.address.zip_code' => 'nullable|string|max:20',
                'paymentBoleto.payer.address.street_name' => 'nullable|string|max:255',
                'paymentBoleto.payer.address.street_number' => 'nullable|string|max:50',
                'paymentBoleto.payer.address.neighborhood' => 'nullable|string|max:255',
                'paymentBoleto.payer.address.city' => 'nullable|string|max:255',
                'paymentBoleto.payer.address.federal_unit' => 'nullable|string|max:2',
                'paymentBoleto.model_type' => 'required',
                'paymentBoleto.model_id' => 'required',
            ],

            "paypal" => [
                'paymentBoleto.amount' => 'required|numeric|min:5.00',
                'paymentBoleto.description' => 'required|string|max:400',
                'paymentBoleto.payer.email' => 'required|email',
                'paymentBoleto.payer.first_name' => 'required|string|max:255',
                'paymentBoleto.payer.last_name' => 'nullable|string|max:255',
                'paymentBoleto.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'paymentBoleto.payer.identification.number' => 'required|string',
                'paymentBoleto.payer.address.zip_code' => 'nullable|string|max:20',
                'paymentBoleto.payer.address.street_name' => 'nullable|string|max:255',
                'paymentBoleto.payer.address.street_number' => 'nullable|string|max:50',
                'paymentBoleto.payer.address.neighborhood' => 'nullable|string|max:255',
                'paymentBoleto.payer.address.city' => 'nullable|string|max:255',
                'paymentBoleto.payer.address.federal_unit' => 'nullable|string|max:2',
                'paymentBoleto.model_type' => 'required',
                'paymentBoleto.model_id' => 'required',
            ],
        ];

        $modelType = $this->input('paymentBoleto.model_type');

        if ($modelType == 'courses') {
            $validator[$activeGateway]['paymentBoleto.model_id'] = 'exists:courses,id';
        }

        return $validator[$activeGateway];
    }

    public function attributes(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $attributes = [
            "mercado_pago" => [
                'paymentBoleto.amount' => 'valor do pagamento',
                'paymentBoleto.description' => 'descrição do pagamento',
                'paymentBoleto.payer.email' => 'e-mail do pagador',
                'paymentBoleto.payer.first_name' => 'nome do pagador',
                'paymentBoleto.payer.last_name' => 'sobrenome do pagador',
                'paymentBoleto.payer.identification.type' => 'tipo de documento',
                'paymentBoleto.payer.identification.number' => 'número do documento',
                'paymentBoleto.payer.address.zip_code' => 'CEP',
                'paymentBoleto.payer.address.street_name' => 'rua',
                'paymentBoleto.payer.address.street_number' => 'número',
                'paymentBoleto.payer.address.neighborhood' => 'bairro',
                'paymentBoleto.payer.address.city' => 'cidade',
                'paymentBoleto.payer.address.federal_unit' => 'UF',
                'paymentBoleto.model_type' => 'tipo de modelo',
                'paymentBoleto.model_id' => 'id do modelo',
            ],

            "stripe" => [
                'paymentBoleto.amount' => 'valor do pagamento',
                'paymentBoleto.description' => 'descrição do pagamento',
                'paymentBoleto.payer.email' => 'e-mail do pagador',
                'paymentBoleto.payer.first_name' => 'nome do pagador',
                'paymentBoleto.payer.last_name' => 'sobrenome do pagador',
                'paymentBoleto.payer.identification.type' => 'tipo de documento',
                'paymentBoleto.payer.identification.number' => 'número do documento',
                'paymentBoleto.payer.address.zip_code' => 'CEP',
                'paymentBoleto.payer.address.street_name' => 'rua',
                'paymentBoleto.payer.address.street_number' => 'número',
                'paymentBoleto.payer.address.neighborhood' => 'bairro',
                'paymentBoleto.payer.address.city' => 'cidade',
                'paymentBoleto.payer.address.federal_unit' => 'UF',
                'paymentBoleto.model_type' => 'tipo de modelo',
                'paymentBoleto.model_id' => 'id do modelo',
            ],

            "paypal" => [
                'paymentBoleto.amount' => 'valor do pagamento',
                'paymentBoleto.description' => 'descrição do pagamento',
                'paymentBoleto.payer.email' => 'e-mail do pagador',
                'paymentBoleto.payer.first_name' => 'nome do pagador',
                'paymentBoleto.payer.last_name' => 'sobrenome do pagador',
                'paymentBoleto.payer.identification.type' => 'tipo de documento',
                'paymentBoleto.payer.identification.number' => 'número do documento',
                'paymentBoleto.payer.address.zip_code' => 'CEP',
                'paymentBoleto.payer.address.street_name' => 'rua',
                'paymentBoleto.payer.address.street_number' => 'número',
                'paymentBoleto.payer.address.neighborhood' => 'bairro',
                'paymentBoleto.payer.address.city' => 'cidade',
                'paymentBoleto.payer.address.federal_unit' => 'UF',
                'paymentBoleto.model_type' => 'tipo de modelo',
                'paymentBoleto.model_id' => 'id do modelo',
            ],
        ];

        return $attributes[$activeGateway] ?? [];
    }

    public function messages(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $messages = [
            "mercado_pago" => [
                'paymentBoleto.amount.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.amount.numeric' => 'O :attribute deve ser um número.',
                'paymentBoleto.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'paymentBoleto.description.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.payer.email.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'paymentBoleto.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
            ],

            "stripe" => [
                'paymentBoleto.amount.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.amount.numeric' => 'O :attribute deve ser um número.',
                'paymentBoleto.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'paymentBoleto.description.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.description.max' => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentBoleto.payer.email.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'paymentBoleto.payer.first_name.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
            ],

            "paypal" => [
                'paymentBoleto.amount.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.amount.numeric' => 'O :attribute deve ser um número.',
                'paymentBoleto.amount.min' => 'O :attribute deve ser no mínimo :min.',
                'paymentBoleto.description.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.description.max' => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentBoleto.payer.email.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.payer.email.email' => 'O :attribute deve ser um e-mail válido.',
                'paymentBoleto.payer.first_name.required' => 'O :attribute é obrigatório.',
                'paymentBoleto.payer.identification.type.in' => 'O :attribute deve ser CPF ou CNPJ.',
            ],
        ];

        return $messages[$activeGateway] ?? [];
    }
}
