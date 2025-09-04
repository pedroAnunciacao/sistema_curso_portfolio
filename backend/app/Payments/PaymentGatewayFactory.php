<?php

namespace App\Payments;

use App\Payments\Contracts\PaymentGatewayInterface;
use Exception;

class PaymentGatewayFactory
{
    /**
     * Cria a instância do gateway de pagamento do cliente.
     *
     * @return PaymentGatewayInterface
     * @throws Exception
     */
    public static function create(): PaymentGatewayInterface
    {
        $className = self::resolveClassName();

        if (!class_exists($className)) {
            throw new Exception("Classe de gateway '{$className}' não encontrada.");
        }

        $instance = new $className();

        if (!$instance instanceof PaymentGatewayInterface) {
            throw new Exception("Classe '{$className}' deve implementar PaymentGatewayInterface.");
        }

        return $instance;
    }

    /**
     * Retorna o nome completo da classe do gateway ativo do cliente
     *
     * @return string
     */
    private static function resolveClassName(): string
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');
        $class = app('client')->gateways("config.payments.integrations.gateways.{$activeGateway}.class");

        return "App\\Payments\\{$class}";
    }
}
