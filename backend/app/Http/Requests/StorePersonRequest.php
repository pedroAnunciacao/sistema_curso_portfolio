<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ContactType;
use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
{
    public function rules()
    {
        $contacts = $this->input('person.contacts') ?? [];

        $rules = [
            'person.name' => ['required', 'string', 'max:100'],
            'person.addresses' => ['sometimes', 'required', 'array'],
            'person.addresses.*.zip_code' => ['required'],
            'person.addresses.*.street' => ['required'],
            'person.addresses.*.no_number' => ['required', 'boolean'],
            'person.addresses.*.number' => ['required_unless:person.addresses.*.no_number,true', 'nullable', 'numeric'],
            'person.addresses.*.complement' => ['nullable', 'string'],
            'person.addresses.*.neighborhood' => ['required', 'string'],
            'person.addresses.*.default' => ['boolean'],
            'person.addresses.*.city_id' => ['required', 'exists:cities,id'],

            'person.contacts' => ['sometimes', 'required', 'array'],
            'person.contacts.*.contact_type_id' => ['required', 'exists:contacts_types,id'],
        ];

        foreach ($contacts as $key => $contact) {
            $type = $contact['contact_type_id'];
            $name = "person.contacts.$key.content";

            switch ($type) {
                case ContactType::Email:
                    $rules[$name] = ['required', 'email:rfc,filter,dns', 'max:100'];
                    break;
                case ContactType::Landline:
                case ContactType::CommercialPhone:
                    $rules[$name] = ['required', 'regex:/^\+?[0-9]{10,15}$/'];
                    break;
                case ContactType::Cellphone:
                    $rules[$name] = ['required', 'regex:/^\+?[0-9]{10,15}$/'];
                    break;
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'person.contacts.*.content.phone' => 'One or more phone numbers are invalid.',
            'person.contacts.*.content.email' => 'One or more email addresses are invalid.',
        ];
    }
}
