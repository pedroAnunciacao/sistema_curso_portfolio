<?php

namespace App\Traits;

trait ResolvesTransactionId
{
    protected function resolveTransactionId(array $response): ?string
    {
        $gateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        $map = [
            'mercado_pago' => 'id',
            'paypal'       => 'identify_transaction_id',
            'stripe'       => 'id',
        ];

        $field = $map[$gateway] ?? 'id'; 

        return $response[$field] ?? null;
    }
}
