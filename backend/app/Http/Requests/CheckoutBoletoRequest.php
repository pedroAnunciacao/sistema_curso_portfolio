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
        return [
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

        ];
    }
}
