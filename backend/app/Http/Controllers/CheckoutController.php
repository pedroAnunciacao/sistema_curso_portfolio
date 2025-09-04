<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use App\Http\Requests\CheckoutPixRequest;
use App\Http\Requests\CheckoutCardRequest;
use App\Http\Requests\CheckoutBoletoRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Checkout;
use Illuminate\Http\Request;


class CheckoutController extends Controller
{
    protected $service;

    public function __construct(CheckoutService $service)
    {
        $this->service = $service;
    }


        public function index(Request $request)
    {

        return $this->service->index($request);
    }



    public function pix(CheckoutPixRequest $request)
    {
        try {
            $data = $this->service->createPix($request->checkoutPix);
            return response()->json(['message' => 'PIX criado com sucesso!', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Pix', $request->all());
        }
    }

    public function card(CheckoutCardRequest $request)
    {
        try {
            $data = $this->service->createCard($request->checkoutCard);
            return response()->json(['message' => 'Pagamento com cartão aprovado!', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Card', $request->all());
        }
    }

    public function boleto(CheckoutBoletoRequest $request)
    {
        try {
            $data = $this->service->createBoleto($request->checkoutBoleto);
            return response()->json(['message' => 'Boleto criado com sucesso!', 'data' => $data]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Boleto', $request->all());
        }
    }

    private function handleException(\Exception $e, string $methodName, array $extra = [])
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
