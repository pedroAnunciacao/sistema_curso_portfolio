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

class PaymentController extends Controller
{
    protected $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }


    public function processPixPayment(ProcessPixPaymentRequest $request): JsonResponse
    {
        try {
            $data = $this->service->processPixPayment($request->paymentPix);
            return response()->json(['message' => 'PIX criado com sucesso!', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Pix', $request->all());
        }
    }

    public function processCardPayment(ProcessCardPaymentRequest $request): JsonResponse
    {
        try {
            $data = $this->service->processCardPayment($request->paymentCard);
            return response()->json(['message' => 'Pagamento com cartão aprovado!', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Card', $request->all());
        }
    }

    public function ProcessBoletoPayment(ProcessBoletoPaymentRequest $request): JsonResponse
    {
        try {
            $data = $this->service->ProcessBoletoPayment($request->paymentBoleto);
            return response()->json(['message' => 'Boleto criado com sucesso!', 'data' => $data]);
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
