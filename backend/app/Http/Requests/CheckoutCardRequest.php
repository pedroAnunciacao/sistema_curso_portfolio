<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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

        ];
    }
}
