<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Models\State;

class EstadoController extends Controller
{
    protected $repository;

    public function __construct(State $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return StateResource::collection($this->repository->all());
    }
}
