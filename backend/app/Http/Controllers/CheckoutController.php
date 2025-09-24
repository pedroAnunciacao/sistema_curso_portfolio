<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use App\Http\Requests\Checkout\CheckoutPixRequest;
use App\Http\Requests\Checkout\CheckoutCardRequest;
use App\Http\Requests\Checkout\CheckoutBoletoRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    protected $service;

    public function __construct(CheckoutService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): AnonymousResourceCollection
    {

        return $this->service->index($request);
    }

    public function pix(CheckoutPixRequest $request): JsonResponse
    {
        try {
            $data = $this->service->createPix($request->checkoutPix);
            return response()->json(['message' => 'PIX criado com sucesso!', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Pix', $request->all());
        }
    }

    public function card(CheckoutCardRequest $request): JsonResponse
    {
        try {
            $data = $this->service->createCard($request->checkoutCard);
            return response()->json(['message' => 'Pagamento com cartão aprovado!', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Card', $request->all());
        }
    }

    public function boleto(CheckoutBoletoRequest $request): JsonResponse
    {
        try {
            $data = $this->service->createBoleto($request->checkoutBoleto);
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
}
