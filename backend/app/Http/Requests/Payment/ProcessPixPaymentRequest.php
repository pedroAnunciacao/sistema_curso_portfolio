<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPixPaymentRequest extends FormRequest
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
                'paymentPix.amount' => 'required|numeric|min:0.01',
                'paymentPix.description' => 'required|string|max:255',
                'paymentPix.payer.email' => 'required|email',
                'paymentPix.payer.first_name' => 'nullable|string|max:255',
                'paymentPix.payer.last_name' => 'nullable|string|max:255',
                'paymentPix.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'paymentPix.payer.identification.number' => 'required|string',
                'paymentPix.expiration_minutes' => 'nullable|integer|min:1',
                'paymentPix.model_type' => 'required',
                'paymentPix.model_id' => 'required',
            ],

            "stripe" => [
                'paymentPix.amount' => 'required|numeric|min:1.00',
                'paymentPix.description' => 'required|string|max:500',
                'paymentPix.payer.email' => 'required|email',
                'paymentPix.payer.first_name' => 'required|string|max:255',
                'paymentPix.payer.last_name' => 'nullable|string|max:255',
                'paymentPix.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'paymentPix.payer.identification.number' => 'required|string',
                'paymentPix.expiration_minutes' => 'nullable|integer|min:1',
                'paymentPix.model_type' => 'required',
                'paymentPix.model_id' => 'required',
            ],

            "paypal" => [
                'paymentPix.amount' => 'required|numeric|min:5.00',
                'paymentPix.description' => 'required|string|max:400',
                'paymentPix.payer.email' => 'required|email',
                'paymentPix.payer.first_name' => 'required|string|max:255',
                'paymentPix.payer.last_name' => 'nullable|string|max:255',
                'paymentPix.payer.identification.type' => 'required|string|in:CPF,CNPJ',
                'paymentPix.payer.identification.number' => 'required|string',
                'paymentPix.expiration_minutes' => 'nullable|integer|min:1',
                'paymentPix.model_type' => 'required',
                'paymentPix.model_id' => 'required',
            ],

        ];

        $modelType = $this->input('paymentPix.model_type');

        if ($modelType == 'courses') {
            $validator[$activeGateway]['paymentPix.model_id'] = 'exists:courses,id';
        }

        return $validator[$activeGateway];
    }


    public function attributes(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $attributes = [
            "mercado_pago" => [
                'paymentPix.amount' => 'valor do pagamento',
                'paymentPix.description' => 'descrição do pagamento',
                'paymentPix.payer.email' => 'e-mail do pagador',
                'paymentPix.payer.first_name' => 'nome do pagador',
                'paymentPix.payer.last_name' => 'sobrenome do pagador',
                'paymentPix.payer.identification.type' => 'tipo de documento',
                'paymentPix.payer.identification.number' => 'número do documento',
                'paymentPix.expiration_minutes' => 'expiração do pagamento',
                'paymentPix.model_type' => 'tipo de modelo',
                'paymentPix.model_id' => 'id do modelo',
            ],
            "stripe" => [
                'paymentPix.amount' => 'valor do pagamento',
                'paymentPix.description' => 'descrição do pagamento',
                'paymentPix.payer.email' => 'e-mail do pagador',
                'paymentPix.payer.first_name' => 'nome do pagador',
                'paymentPix.payer.last_name' => 'sobrenome do pagador',
                'paymentPix.payer.identification.type' => 'tipo de documento',
                'paymentPix.payer.identification.number' => 'número do documento',
                'paymentPix.expiration_minutes' => 'expiração do pagamento',
                'paymentPix.model_type' => 'tipo de modelo',
                'paymentPix.model_id' => 'id do modelo',
            ],
            "paypal" => [
                'paymentPix.amount' => 'valor do pagamento',
                'paymentPix.description' => 'descrição do pagamento',
                'paymentPix.payer.email' => 'e-mail do pagador',
                'paymentPix.payer.first_name' => 'nome do pagador',
                'paymentPix.payer.last_name' => 'sobrenome do pagador',
                'paymentPix.payer.identification.type' => 'tipo de documento',
                'paymentPix.payer.identification.number' => 'número do documento',
                'paymentPix.expiration_minutes' => 'expiração do pagamento',
                'paymentPix.model_type' => 'tipo de modelo',
                'paymentPix.model_id' => 'id do modelo',
            ],
        ];

        return $attributes[$activeGateway] ?? [];
    }

    public function messages(): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $messages = [
            "mercado_pago" => [
                'paymentPix.amount.required' => 'O :attribute é obrigatório.',
                'paymentPix.amount.numeric'  => 'O :attribute deve ser um número.',
                'paymentPix.amount.min'      => 'O :attribute deve ser no mínimo :min.',
                'paymentPix.description.required' => 'A :attribute é obrigatória.',
                'paymentPix.description.string'   => 'A :attribute deve ser um texto.',
                'paymentPix.description.max'      => 'A :attribute deve ter no máximo :max caracteres.',
                'paymentPix.payer.email.required' => 'O :attribute é obrigatório.',
                'paymentPix.payer.email.email'    => 'O :attribute deve ser um e-mail válido.',
                'paymentPix.payer.first_name.string' => 'O :attribute deve ser um texto.',
                'paymentPix.payer.first_name.max'    => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentPix.payer.last_name.string'  => 'O :attribute deve ser um texto.',
                'paymentPix.payer.last_name.max'     => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentPix.payer.identification.type.required' => 'O :attribute é obrigatório.',
                'paymentPix.payer.identification.type.in'       => 'O :attribute deve ser CPF ou CNPJ.',
                'paymentPix.payer.identification.number.required' => 'O :attribute é obrigatório.',
                'paymentPix.payer.identification.number.string'   => 'O :attribute deve ser um texto.',
                'paymentPix.expiration_minutes.integer' => 'O :attribute deve ser um número inteiro.',
                'paymentPix.expiration_minutes.min'     => 'O :attribute deve ser no mínimo :min.',
                'paymentPix.model_type.required' => 'O :attribute é obrigatório.',
                'paymentPix.model_id.required'   => 'O :attribute é obrigatório.',
            ],
            "stripe" => [
                'paymentPix.amount.required' => 'O :attribute é obrigatório.',
                'paymentPix.amount.numeric'  => 'O :attribute deve ser um número.',
                'paymentPix.amount.min'      => 'O :attribute deve ser no mínimo :min.',
                'paymentPix.description.required' => 'A :attribute é obrigatória.',
                'paymentPix.description.string'   => 'A :attribute deve ser um texto.',
                'paymentPix.description.max'      => 'A :attribute deve ter no máximo :max caracteres.',
                'paymentPix.payer.email.required' => 'O :attribute é obrigatório.',
                'paymentPix.payer.email.email'    => 'O :attribute deve ser um e-mail válido.',
                'paymentPix.payer.first_name.string' => 'O :attribute deve ser um texto.',
                'paymentPix.payer.first_name.max'    => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentPix.payer.last_name.string'  => 'O :attribute deve ser um texto.',
                'paymentPix.payer.last_name.max'     => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentPix.payer.identification.type.required' => 'O :attribute é obrigatório.',
                'paymentPix.payer.identification.type.in'       => 'O :attribute deve ser CPF ou CNPJ.',
                'paymentPix.payer.identification.number.required' => 'O :attribute é obrigatório.',
                'paymentPix.payer.identification.number.string'   => 'O :attribute deve ser um texto.',
                'paymentPix.expiration_minutes.integer' => 'O :attribute deve ser um número inteiro.',
                'paymentPix.expiration_minutes.min'     => 'O :attribute deve ser no mínimo :min.',
                'paymentPix.model_type.required' => 'O :attribute é obrigatório.',
                'paymentPix.model_id.required'   => 'O :attribute é obrigatório.',
            ],
            "paypal" => [
                'paymentPix.amount.required' => 'O :attribute é obrigatório.',
                'paymentPix.amount.numeric'  => 'O :attribute deve ser um número.',
                'paymentPix.amount.min'      => 'O :attribute deve ser no mínimo :min.',
                'paymentPix.description.required' => 'A :attribute é obrigatória.',
                'paymentPix.description.string'   => 'A :attribute deve ser um texto.',
                'paymentPix.description.max'      => 'A :attribute deve ter no máximo :max caracteres.',
                'paymentPix.payer.email.required' => 'O :attribute é obrigatório.',
                'paymentPix.payer.email.email'    => 'O :attribute deve ser um e-mail válido.',
                'paymentPix.payer.first_name.string' => 'O :attribute deve ser um texto.',
                'paymentPix.payer.first_name.max'    => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentPix.payer.last_name.string'  => 'O :attribute deve ser um texto.',
                'paymentPix.payer.last_name.max'     => 'O :attribute deve ter no máximo :max caracteres.',
                'paymentPix.payer.identification.type.required' => 'O :attribute é obrigatório.',
                'paymentPix.payer.identification.type.in'       => 'O :attribute deve ser CPF ou CNPJ.',
                'paymentPix.payer.identification.number.required' => 'O :attribute é obrigatório.',
                'paymentPix.payer.identification.number.string'   => 'O :attribute deve ser um texto.',
                'paymentPix.expiration_minutes.integer' => 'O :attribute deve ser um número inteiro.',
                'paymentPix.expiration_minutes.min'     => 'O :attribute deve ser no mínimo :min.',
                'paymentPix.model_type.required' => 'O :attribute é obrigatório.',
                'paymentPix.model_id.required'   => 'O :attribute é obrigatório.',
            ],
        ];

        return $messages[$activeGateway] ?? [];
    }
}
