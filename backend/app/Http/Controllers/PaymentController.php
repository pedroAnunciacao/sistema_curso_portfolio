<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Http\Requests\Payment\ProcessBoletoPaymentRequest;
use App\Http\Requests\Payment\ProcessCardPaymentRequest;
use App\Http\Requests\Payment\ProcessPixPaymentRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Checkout\CheckoutResource;

class PaymentController extends Controller
{
    protected $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }


    public function processPixPayment(ProcessPixPaymentRequest $request)
    {
        try {
            $data = $this->service->processPixPayment($request->paymentPix);
            return new CheckoutResource($data);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Pix', $request->all());
        }
    }

    public function processCardPayment(ProcessCardPaymentRequest $request)
    {
        try {
            $data = $this->service->processCardPayment($request->paymentCard);
            return new CheckoutResource($data);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Card', $request->all());
        }
    }

    public function ProcessBoletoPayment(ProcessBoletoPaymentRequest $request)
    {
        try {
            $data = $this->service->ProcessBoletoPayment($request->paymentBoleto);
            return new CheckoutResource($data);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Boleto', $request->all());
        }
    }

    private function handleException(\Exception $e, string $methodName, array $extra = []): JsonResponse
    {
        Log::error("#Checkout{$methodName}", [
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
            'details'   => method_exists($e, 'getDetails') ? $e->getDetails() : null,
            'request'   => $extra
        ]);

        return response()->json(['message' => 'Erro ao processar pagamento'], Response::HTTP_BAD_REQUEST);
    }


    public function postback(Request $request)
    {
        $this->service->postback($request);
    }
}
