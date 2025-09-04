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
        return [
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

        ];
    }
}
