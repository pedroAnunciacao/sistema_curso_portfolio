<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\State\StateResource;
use App\Services\StateService;
use Illuminate\Http\Request;

class StateController extends Controller
{
    protected $service;

    public function __construct(StateService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $queryParams = $request->query('queryParams') ?? [];
        $states = $this->service->index($queryParams);
        return StateResource::collection($states);
    }

    public function show(int $id)
    {
        $state =   $this->service->show($id);
        return new StateResource($state);
    }
}
