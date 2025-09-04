<?php

namespace App\Payments\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Criar pagamento via PIX
     */
    public function createPix(array $payload): array;

    /**
     * Criar pagamento via Cartão
     */
    public function createCard(array $payload): array;

    /**
     * Criar pagamento via Boleto
     */
    public function createBoleto(array $payload): array;
}
