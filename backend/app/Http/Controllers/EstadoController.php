<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\EstadoResource;
use App\Models\Estado;

class EstadoController extends Controller
{
    protected $repository;

    public function __construct(Estado $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return EstadoResource::collection($this->repository->all());
    }
}
