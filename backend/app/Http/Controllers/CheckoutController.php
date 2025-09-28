<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use App\Http\Requests\Checkout\CheckoutUpdateRequest;
use App\Http\Resources\Checkout\CheckoutResource;
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
        $queryParams = $request->query('queryParams') ?? [];
        $checkouts = $this->service->index($queryParams);
        return CheckoutResource::collection($checkouts);
    }

    public function show(int|string $courseId)
    {
        $checkout = $this->service->show($courseId);
        return new CheckoutResource($checkout);
    }

    public function update(CheckoutUpdateRequest $request)
    {
        $checkout = $this->service->update($request->checkout);
        return new CheckoutResource($checkout);
    }

    public function destroy(int|string $id)
    {
        $checkout = $this->service->destroy($id);
        return new CheckoutResource($checkout);
    }

    public function restore(int $id)
    {
        return  $this->service->restore($id);
    }
}
