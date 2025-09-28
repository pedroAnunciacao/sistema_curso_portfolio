<?php

namespace App\Payments\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Criar pagamento via PIX
     */
    public function processPixPayment(array $payload): array;

    /**
     * Criar pagamento via Cartão
     */
    public function processCardPayment(array $payload): array;

    /**
     * Criar pagamento via Boleto
     */
    public function processBoletoPayment(array $payload): array;
}
