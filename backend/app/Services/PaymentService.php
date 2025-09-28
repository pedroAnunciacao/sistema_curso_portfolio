<?php

namespace App\Services;

use App\Traits\PaymentLogger;
use App\Payments\PaymentGatewayFactory;
use App\Models\Checkout;
use App\Traits\ResolvesTransactionId;
use App\Traits\ResolveTeacherIdForModelType;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class PaymentService
{
    use PaymentLogger, ResolvesTransactionId, ResolveTeacherIdForModelType;

    protected $adapter;
    protected $exceptionClass;
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->adapter = PaymentGatewayFactory::create();
        $this->exceptionClass = $this->adapter::$exceptionClass ?? \Exception::class;
        $this->checkoutService = $checkoutService;
    }

    public function processPixPayment(array $payload): Checkout
    {
        $startTime = now()->toDateTimeString();

        try {
            $pix = $this->adapter->processPixPayment($payload);
            $checkout = $this->checkoutService->store([
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

            return $checkout;
        } catch (\Exception $e) {
            $this->logError('Pix', $e, ['request' => $payload, 'inicio' => $startTime]);
            throw $e;
        }
    }

    public function processCardPayment(array $payload): Checkout
    {
        $startTime = now()->toDateTimeString();

        try {
            $card = $this->adapter->processCardPayment($payload);
            $checkout = $this->checkoutService->store([
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

    public function processBoletoPayment(array $payload): Checkout
    {
        $startTime = now()->toDateTimeString();

        try {
            $boleto = $this->adapter->processBoletoPayment($payload);
            $checkout = $this->checkoutService->store([
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

    public function postback(Request $request)
    {
        response()->noContent();
    }
}
