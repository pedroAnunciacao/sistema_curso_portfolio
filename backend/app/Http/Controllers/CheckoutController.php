<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Payments\PaymentGatewayFactory;

class CheckoutController extends Controller
{
    protected $repository;
    private $checkoutService;
    private $statusService;

    public function __construct() {}

    public function createPix()
    {



        $adapter = PaymentGatewayFactory::create();

        try {
            $pixPayment = $adapter->createPix(
                100.50, // valor
                'Produto teste PIX', // descrição
                [
                    'email' => 'comprador@email.com',
                    'first_name' => 'João',
                    'last_name' => 'Silva',
                    'identification' => [
                        'type' => 'CPF',
                        'number' => '12345678909'
                    ]
                ],
                60 // expira em 60 minutos (opcional)
            );



            $checkoutPixobject = (object)$pixPayment;

            return response()->json([
                'message' => 'PIX criado com sucesso!',
                'id' => $checkoutPixobject->id,
                'status' => $checkoutPixobject->status,
                'qr_code' => $checkoutPixobject->qr_code,
                'qr_code_base64' => $checkoutPixobject->qr_code_base64,
            ]);
        } catch (\Exception $e) {

            Log::info('#UpdateModelTransaction', [$e]);

            dd($e);

            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function postback(Request $request)
    {

                Log::info('#Checkoutpostback', [$request]);

        // return response()->json(['aqui Ngrokc'], Response::HTTP_OK);
    }


    public function card()
    {
        $adapter = PaymentGatewayFactory::create();

        try {
            $cardPayment = $adapter->createCard(
                100.50,
                'Produto teste Cartão',
                [
                    'email' => 'comprador@email.com',
                    'first_name' => 'João',
                    'last_name' => 'Silva',
                    'identification' => [
                        'type' => 'CPF',
                        'number' => '12345678909'
                    ]
                ],
                [
                    'id' => 'f5ca8c517d5bf90b0257a1bc08c210d0',
                    'card_number' => '5031 4332 1540 6351' // necessário para pegar o BIN
                ],
                1
            );

            $checkoutCardObject = (object)$cardPayment;
            return response()->json([
                'message' => 'Cartão criado com sucesso!',
                'id' => $checkoutCardObject->id,
                'status' => $checkoutCardObject->status,
                'status_detail' => $checkoutCardObject->status_detail,
                'transaction_amount' => $checkoutCardObject->transaction_amount,
                'installments' => $checkoutCardObject->installments,
            ]);
        } catch (\Exception $e) {
            Log::error('#CheckoutCard', [$e]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
