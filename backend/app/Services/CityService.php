<?php

namespace App\Services;

use App\Repositories\Contracts\CityRepositoryInterface;

class CityService
{
    protected CityRepositoryInterface $repository;

    public function __construct(CityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(array $queryParams)
    {
        return $this->repository->index($queryParams);
    }

    public function show(int $id)
    {
        return $this->repository->show($id);
    }

}
