<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CidadeController extends Controller
{
    protected $repository;

    public function __construct(City $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $cidades = QueryBuilder::for(City::class)
            ->allowedFields('id', 'nome')
            ->allowedFilters(AllowedFilter::exact('codigo'), AllowedFilter::exact('estado_id'))
            ->get();

        return CityResource::collection($cidades);
    }

    public function show(int $id)
    {
        $cidade = QueryBuilder::for($this->repository->query())
            ->allowedIncludes('estado')
            ->whereKey($id)
            ->firstOrFail();

        return new CityResource($cidade);
    }
}
