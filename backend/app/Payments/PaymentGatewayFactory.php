<?php

namespace App\Payments;

class PaymentGatewayFactory
{
    static $targetNamespace = "App\\Payments\\";

    public static function create()
    {
        $className = self::getDomainClass();
        $classPath = self::$targetNamespace . $className;

        if ($className !== false && class_exists($classPath)) {
            try {
                $classInstance = new $classPath();
                return $classInstance;
            } catch (\Exception $e) {
                dd($e);
                var_dump($e->getMessage());
            }
        }
        throw new \Exception('Classe não encontrada para o cliente');
        return;
    }

    private static function getDomainClass()
    {
        $adpter = app('client')->gateways('payments.integrations.useConfig.active');
        return app('client')->gateways("payments.integrations.gateways.{$adpter}.class");
    }
}
