<?php

namespace App\Payments;

use App\Payments\Contracts\PaymentGatewayInterface;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Exceptions\MercadoPagoException;

class MercadoPagoAdapter implements PaymentGatewayInterface
{
    public static string $exceptionClass = MercadoPagoException::class;

    protected $client;

    public function __construct()
    {
        $access_token = app('client')->gateways('config.payments.integrations.gateways.mercado_pago.secret_key');
        MercadoPagoConfig::setAccessToken($access_token);
        $this->client = new PaymentClient();
    }

    public function processPixPayment(array $payload): array
    {
        try {
            $amount       = (float) $payload['amount'];
            $description  = $payload['description'];
            $payer        = $payload['payer'];
            $expiration   = $payload['expiration_minutes'] ?? 10;

            $request = [
                "transaction_amount" => $amount,
                "payment_method_id"  => "pix",
                "description"        => $description,
                "payer" => [
                    "email"          => $payer['email'],
                    "first_name"     => $payer['first_name'] ?? '',
                    "last_name"      => $payer['last_name'] ?? '',
                    "identification" => [
                        "type"   => $payer['identification']['type'] ?? 'CPF',
                        "number" => $payer['identification']['number'] ?? ''
                    ]
                ],
                "date_of_expiration" => now()
                    ->addMinutes($expiration)
                    ->format('Y-m-d\TH:i:s.000P')
            ];

            $options = new RequestOptions();
            $idempotencyKey = uniqid('pix_', true);
            $options->setCustomHeaders(["x-idempotency-key" => $idempotencyKey]);

            Log::info('[Pix] Criando pagamento', compact('request', 'idempotencyKey'));

            $payment = $this->client->create($request, $options);

            return [
                'id'                 => $payment->id,
                'status'             => $payment->status,
                'transaction_amount' => $payment->transaction_amount,
                'qr_code'            => $payment->point_of_interaction->transaction_data->qr_code ?? null,
                'qr_code_base64'     => $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null,
                'idempotency_key'    => $idempotencyKey
            ];
        } catch (MPApiException $e) {
            throw MercadoPagoException::fromMpApi($e);
        }
    }

    public function processCardPayment(array $payload): array
    {
        try {
            $amount       = (float) $payload['amount'];
            $description  = $payload['description'];
            $payer        = $payload['payer'];
            $cardToken    = $payload['card'];
            $installments = $payload['installments'] ?? 1;

            $bin = substr($cardToken['card_number'], 0, 6);

            $response = Http::get('https://api.mercadopago.com/v1/payment_methods/search', [
                'bin'        => $bin,
                'public_key' => app('client')->gateways('config.payments.integrations.gateways.mercado_pago.public_key')
            ])->json();

            $cardPaymentMethod = null;
            foreach ($response['results'] as $method) {
                if (($method['payment_type_id'] ?? '') === 'credit_card') {
                    $cardPaymentMethod = $method['id'];
                    break;
                }
            }

            $request = [
                "transaction_amount" => $amount,
                "token"              => $cardToken['id'],
                "installments"       => $installments,
                "payment_method_id"  => $cardPaymentMethod,
                "description"        => $description,
                "payer" => [
                    "email"          => $payer['email'],
                    "first_name"     => $payer['first_name'] ?? '',
                    "last_name"      => $payer['last_name'] ?? '',
                    "identification" => [
                        "type"   => $payer['identification']['type'] ?? 'CPF',
                        "number" => $payer['identification']['number'] ?? ''
                    ]
                ]
            ];

            $options = new RequestOptions();
            $idempotencyKey = uniqid('card_', true);
            $options->setCustomHeaders(["x-idempotency-key" => $idempotencyKey]);

            Log::info('[Card] Criando pagamento', compact('request', 'idempotencyKey'));

            $payment = $this->client->create($request, $options);

            return [
                'id'                 => $payment->id,
                'status'             => $payment->status,
                'status_detail'      => $payment->status_detail,
                'transaction_amount' => $payment->transaction_amount,
                'installments'       => $payment->installments,
                'idempotency_key'    => $idempotencyKey
            ];
        } catch (MPApiException $e) {
            throw MercadoPagoException::fromMpApi($e);
        }
    }

    public function processBoletoPayment(array $payload): array
    {
        try {
            $amount      = (float) $payload['amount'];
            $description = $payload['description'];
            $payer       = $payload['payer'];

            $request = [
                "transaction_amount" => $amount,
                "description"        => $description,
                "payment_method_id"  => "bolbradesco",
                "payer" => [
                    "email"          => $payer['email'],
                    "first_name"     => $payer['first_name'] ?? '',
                    "last_name"      => $payer['last_name'] ?? '',
                    "identification" => [
                        "type"   => $payer['identification']['type'] ?? 'CPF',
                        "number" => $payer['identification']['number'] ?? ''
                    ],
                    "address"        => $payer['address'] ?? []
                ]
            ];

            $options = new RequestOptions();
            $idempotencyKey = uniqid('boleto_', true);
            $options->setCustomHeaders(["x-idempotency-key" => $idempotencyKey]);

            Log::info('[Boleto] Criando pagamento', compact('request', 'idempotencyKey'));

            $payment = $this->client->create($request, $options);

            $transactionDetails = $payment->getResponse()->getContent()['transaction_details'] ?? [];

            return [
                'id'                 => $payment->id,
                'status'             => $payment->status,
                'status_detail'      => $payment->status_detail,
                'transaction_amount' => $payment->transaction_amount,
                'barcode'            => $transactionDetails['barcode']['content'] ?? null,
                'ticket_url'         => $payment->transaction_details->external_resource_url ?? null,
                'idempotency_key'    => $idempotencyKey
            ];
        } catch (MPApiException $e) {
            throw MercadoPagoException::fromMpApi($e);
        }
    }
}
