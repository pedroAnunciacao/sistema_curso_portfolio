<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\WebhooksService;
use Illuminate\Http\Request;

class WebhooksController extends Controller
{
    protected $service;

    public function __construct(WebhooksService $service)
    {
        $this->service = $service;
    }

    public function postbackMercadoPago(Request $request)
    {
        $this->service->postbackMercadoPago($request);
    }


        public function postback(Request $request)
    {
        $this->service->postback($request);
    }
}
