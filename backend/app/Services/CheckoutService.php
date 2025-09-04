<?php

namespace App\Services;

use App\Traits\PaymentLogger;
use App\Payments\PaymentGatewayFactory;
use App\Repositories\CheckoutRepository;
use Illuminate\Http\Request;

class CheckoutService
{
    use PaymentLogger;

    protected $adapter;
    protected $exceptionClass;
    protected $repository;

    public function __construct(CheckoutRepository $repository)
    {
        $this->adapter = PaymentGatewayFactory::create();
        $this->exceptionClass = $this->adapter::$exceptionClass ?? \Exception::class;
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        return $this->repository->index($request);
    }


    public function createPix(array $payload)
    {
        $startTime = now()->toDateTimeString();

        try {
            $pix = $this->adapter->createPix($payload);
            $checkout = $this->repository->create([
                'transaction_id' => $pix['id'],
                'method' => 'pix',
                'status' => $pix['status'],
                'model_type' => $payload['model_type'] ?? null,
                'model_id' => $payload['model_id'] ?? null,
                'data' => $pix
            ]);

            $endTime = now()->toDateTimeString();
            $this->logSuccess('Pix', $pix, $startTime, $endTime);

            return $checkout; // retorna o model salvo
        } catch (\Exception $e) {
            $this->logError('Pix', $e, ['request' => $payload, 'inicio' => $startTime]);
            throw $e;
        }
    }

    public function createCard(array $payload)
    {
        $startTime = now()->toDateTimeString();

        try {
            $card = $this->adapter->createCard($payload);
            $checkout = $this->repository->create([
                'transaction_id' => $card['id'],
                'method' => 'card',
                'status' => $card['status'],
                'model_type' => $payload['model_type'] ?? null,
                'model_id' => $payload['model_id'] ?? null,
                'data' => $card
            ]);

            $endTime = now()->toDateTimeString();
            $this->logSuccess('Card', $card, $startTime, $endTime);

            return $checkout;
        } catch (\Exception $e) {
            $this->logError('Card', $e, ['request' => $payload, 'inicio' => $startTime]);
            throw $e;
        }
    }

    public function createBoleto(array $payload)
    {
        $startTime = now()->toDateTimeString();

        try {
            $boleto = $this->adapter->createBoleto($payload);
            $checkout = $this->repository->create([
                'transaction_id' => $boleto['id'],
                'method' => 'boleto',
                'status' => $boleto['status'],
                'model_type' => $payload['model_type'] ?? null,
                'model_id' => $payload['model_id'] ?? null,
                'data' => $boleto
            ]);

            $endTime = now()->toDateTimeString();
            $this->logSuccess('Boleto', $boleto, $startTime, $endTime);

            return $checkout;
        } catch (\Exception $e) {
            $this->logError('Boleto', $e, ['request' => $payload, 'inicio' => $startTime]);
            throw $e;
        }
    }
}
