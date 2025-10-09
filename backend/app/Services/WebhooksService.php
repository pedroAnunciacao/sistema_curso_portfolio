<?php

namespace App\Services;

use App\Traits\PaymentLogger;
use App\Payments\PaymentGatewayFactory;
use App\Models\Checkout;
use App\Traits\ResolvesTransactionId;
use App\Traits\ResolveTeacherIdForModelType;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhooksService
{

    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function postbackMercadoPago(Request $request)
    {
        Log::info([$request->all()]);
        $data = $request->all();
        $checkout = $this->checkoutService->findByTransactionId($data['data']['id']);

        $dataUpdate = [
            'id' => $checkout->id,
            'status' => 'paid'
        ];

        $this->checkoutService->update($dataUpdate);
        Log::info([$checkout]);

        response()->noContent();
    }

    public function postback(Request $request)
    {
        Log::info($request);

        response()->noContent();
    }
}
