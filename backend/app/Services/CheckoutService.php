<?php

namespace App\Services;

use App\Traits\PaymentLogger;
use App\Payments\PaymentGatewayFactory;
use App\Repositories\CheckoutRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Checkout;
use App\Traits\ResolvesTransactionId;
use App\Traits\ResolveTeacherIdForModelType;

class CheckoutService
{
    use PaymentLogger, ResolvesTransactionId, ResolveTeacherIdForModelType;

    protected $adapter;
    protected $exceptionClass;
    protected $repository;

    public function __construct(CheckoutRepository $repository)
    {
        $this->adapter = PaymentGatewayFactory::create();
        $this->exceptionClass = $this->adapter::$exceptionClass ?? \Exception::class;
        $this->repository = $repository;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->repository->index($request);
    }


    public function createPix(array $payload): Checkout
    {
        $startTime = now()->toDateTimeString();

        try {
            $pix = $this->adapter->createPix($payload);
            $checkout = $this->repository->create([
                'transaction_id' => $this->resolveTransactionId($pix),
                'method' => 'pix',
                'status' => $pix['status'],
                'model_type' => $payload['model_type'] ?? null,
                'model_id' => $payload['model_id'] ?? null,
                'teacher_id' => $this->resolveTeacherId($payload['model_type'], $payload['model_id']),
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

    public function createCard(array $payload): Checkout
    {
        $startTime = now()->toDateTimeString();

        try {
            $card = $this->adapter->createCard($payload);
            $checkout = $this->repository->create([
                'transaction_id' => $this->resolveTransactionId($card),
                'method' => 'card',
                'status' => $card['status'],
                'model_type' => $payload['model_type'] ?? null,
                'model_id' => $payload['model_id'] ?? null,
                'teacher_id' => $this->resolveTeacherId($payload['model_type'], $payload['model_id']),

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

    public function createBoleto(array $payload): Checkout
    {
        $startTime = now()->toDateTimeString();

        try {
            $boleto = $this->adapter->createBoleto($payload);
            $checkout = $this->repository->create([
                'transaction_id' => $this->resolveTransactionId($boleto),
                'method' => 'boleto',
                'status' => $boleto['status'],
                'model_type' => $payload['model_type'] ?? null,
                'model_id' => $payload['model_id'] ?? null,
                'teacher_id' => $this->resolveTeacherId($payload['model_type'], $payload['model_id']),

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
