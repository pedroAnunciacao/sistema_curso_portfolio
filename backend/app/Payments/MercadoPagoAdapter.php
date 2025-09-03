<?php

namespace App\Payments;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MercadoPagoAdapter
{
    protected $config;
    protected $client;

    public function __construct()
    {
        $access_token = app('client')->gateways('payments.integrations.gateways.mercado_pago.access_token');

        MercadoPagoConfig::setAccessToken($access_token);
        $this->client = new PaymentClient();
    }




    public function createPix($amount, $description, $payer)
    {
        try {
            // Monta o request do pagamento
            $request = [
                "transaction_amount" => (float) $amount,
                "payment_method_id" => "pix",
                "description" => $description,
                "payer" => [
                    "email" => $payer['email'],
                    "first_name" => $payer['first_name'] ?? '',
                    "last_name" => $payer['last_name'] ?? '',
                    "identification" => [
                        "type" => $payer['identification']['type'] ?? 'CPF',
                        "number" => $payer['identification']['number'] ?? ''
                    ]
                ]
            ];

            // Cria RequestOptions com header de idempotency
            $requestOptions = new RequestOptions();
            $idempotencyKey = uniqid('pix_', true);
            $requestOptions->setCustomHeaders([
                "x-idempotency-key" => $idempotencyKey
            ]);

            Log::info('[Pix] Criando pagamento', [
                'request' => $request,
                'idempotency_key' => $idempotencyKey
            ]);

            // Cria o pagamento via client DX PHP
            $payment = $this->client->create($request, $requestOptions);

            // Retorna dados essenciais do Pix para frontend
            $result = [
                'id' => $payment->id,
                'status' => $payment->status,
                'transaction_amount' => $payment->transaction_amount,
                'qr_code' => $payment->point_of_interaction->transaction_data->qr_code ?? null,
                'qr_code_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null,
                'idempotency_key' => $idempotencyKey
            ];

            Log::info('[Pix] Pagamento criado com sucesso', ['result' => $result]);

            return $result;
        } catch (MPApiException $e) {
            Log::error('[Pix] Erro ao criar pagamento', ['exception' => $e]);
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'details' => $e->getApiResponse()
            ];
        }
    }


    // public function createCard($amount, $description, $payer, $token, $installments = 1, $paymentMethodId = 'visa')
    // {
    //     try {
    //         // Monta o request do pagamento com cartão
    //         $request = [
    //             "transaction_amount" => (float) $amount,
    //             "token" => $token, // token gerado no frontend
    //             "installments" => (int) $installments,
    //             "payment_method_id" => 'Mastercard', // ex: visa, mastercard...
    //             "description" => $description,
    //             "payer" => [
    //                 "email" => $payer['email'],
    //                 "first_name" => $payer['first_name'] ?? '',
    //                 "last_name" => $payer['last_name'] ?? '',
    //                 "identification" => [
    //                     "type" => $payer['identification']['type'] ?? 'CPF',
    //                     "number" => $payer['identification']['number'] ?? ''
    //                 ]
    //             ]
    //         ];

    //         // Cria RequestOptions com idempotency
    //         $requestOptions = new RequestOptions();
    //         $idempotencyKey = uniqid('card_', true);
    //         $requestOptions->setCustomHeaders([
    //             "x-idempotency-key" => $idempotencyKey
    //         ]);

    //         Log::info('[Card] Criando pagamento', [
    //             'request' => $request,
    //             'idempotency_key' => $idempotencyKey
    //         ]);

    //         // Cria o pagamento via client
    //         $payment = $this->client->create($request, $requestOptions);

    //         // Retorna dados essenciais do pagamento de cartão
    //         $result = [
    //             'id' => $payment->id,
    //             'status' => $payment->status,
    //             'status_detail' => $payment->status_detail,
    //             'transaction_amount' => $payment->transaction_amount,
    //             'installments' => $payment->installments,
    //             'idempotency_key' => $idempotencyKey
    //         ];

    //         Log::info('[Card] Pagamento criado com sucesso', ['result' => $result]);

    //         return $result;

    //     } catch (MPApiException $e) {
    //         Log::error('[Card] Erro ao criar pagamento', ['exception' => $e]);
    //         return [
    //             'error' => true,
    //             'message' => $e->getMessage(),
    //             'details' => $e->getApiResponse()
    //         ];
    //     }
    // }


    public function createCard($amount, $description, $payer, $cardToken, $installments = 1)
    {
        try {
$bin = substr($cardToken['card_number'], 0, 6);

$response = Http::get('https://api.mercadopago.com/v1/payment_methods/search', [
    'bin' => $bin,
    'public_key' => app('client')->gateways('payments.integrations.gateways.mercado_pago.public_key')
])->json();

$cardPaymentMethod = null;

foreach ($response['results'] as $method) {
    if (($method['payment_type_id'] ?? '') === 'credit_card') {
        $cardPaymentMethod = $method['id']; // ex: 'master', 'visa'
        break;
    }
}

            // Monta o request de 
            $request = [
                "transaction_amount" => (float) $amount,
                "token" => $cardToken['id'], // token do cartão
                "installments" => (int) $installments,
                "payment_method_id" => $cardPaymentMethod,
                "description" => $description,
                "payer" => [
                    "email" => $payer['email'],
                    "first_name" => $payer['first_name'] ?? '',
                    "last_name" => $payer['last_name'] ?? '',
                    "identification" => [
                        "type" => $payer['identification']['type'] ?? 'CPF',
                        "number" => $payer['identification']['number'] ?? ''
                    ]
                ],

            ];

            $requestOptions = new RequestOptions();
            $idempotencyKey = uniqid('card_', true);
            $requestOptions->setCustomHeaders([
                "x-idempotency-key" => $idempotencyKey
            ]);

            Log::info('[Card] Criando pagamento', [
                'request' => $request,
                'idempotency_key' => $idempotencyKey
            ]);

            // Cria o pagamento via client
            $payment = $this->client->create($request, $requestOptions);

            return [
                'id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'transaction_amount' => $payment->transaction_amount,
                'installments' => $payment->installments,
                'idempotency_key' => $idempotencyKey
            ];
        } catch (\Exception $e) {
            Log::error('[Card] Erro ao criar pagamento', ['exception' => $e]);
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'details' => $e instanceof MPApiException ? $e->getApiResponse() : null
            ];
        }
    }

    public function getPayment($paymentId)
    {
        try {
            return $this->client->get($paymentId);
        } catch (MPApiException $e) {
            throw new \Exception("Erro ao buscar pagamento: " . $e->getMessage());
        }
    }
}
