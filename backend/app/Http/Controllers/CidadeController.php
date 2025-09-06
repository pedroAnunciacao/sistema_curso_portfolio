<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\CidadeResource;
use App\Models\Cidade;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CidadeController extends Controller
{
    protected $repository;

    public function __construct(Cidade $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $cidades = QueryBuilder::for(Cidade::class)
            ->allowedFields('id', 'nome')
            ->allowedFilters(AllowedFilter::exact('codigo'), AllowedFilter::exact('estado_id'))
            ->get();

        return CidadeResource::collection($cidades);
    }

    public function show(int $id)
    {
        $cidade = QueryBuilder::for($this->repository->query())
            ->allowedIncludes('estado')
            ->whereKey($id)
            ->firstOrFail();

        return new CidadeResource($cidade);
    }
}
