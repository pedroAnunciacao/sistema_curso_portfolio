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
    public function __construct(CheckoutService $checkoutService)
    {
    }

    public function postbackMercadoPago(Request $request)
    {
        Log::info([$request->all()]);

        response()->noContent();
    }

        public function postback(Request $request)
    {
        Log::info($request);

        response()->noContent();
    }
}
