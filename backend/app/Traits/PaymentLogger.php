<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait PaymentLogger
{
    protected function logSuccess(string $method, array $data, string $startTime, string $endTime): void
    {
        Log::channel(match (strtolower($method)) {
            'pix'    => 'checkout_pix',
            'card'   => 'checkout_card',
            'boleto' => 'checkout_boleto',
            default  => 'stack',
        })->info("Pagamento {$method} finalizado", [
            'inicio' => $startTime,
            'fim'    => $endTime,
            'data'   => $data
        ]);
    }

    protected function logError(string $method, \Exception $e, array $extra = []): void
    {
        $logData = array_merge([
            'timestamp' => now()->toDateTimeString(),
            'method'    => $method,
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
            'details'   => method_exists($e, 'getDetails') ? $e->getDetails() : null,
        ], $extra);

        Log::channel(match (strtolower($method)) {
            'pix'    => 'checkout_pix',
            'card'   => 'checkout_card',
            'boleto' => 'checkout_boleto',
            default  => 'stack',
        })->error("#Checkout{$method}", $logData);
    }
}
