<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\City\CityResource;
use App\Services\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected $service;

    public function __construct(CityService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $queryParams = $request->query('queryParams') ?? [];

        $cities = $this->service->index($queryParams);

        return CityResource::collection($cities);
    }

    public function show(int $id)
    {
        $cidade = $this->service->show($id);

        return new CityResource($cidade);
    }
}
