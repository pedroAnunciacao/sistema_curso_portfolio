<?php

namespace App\Services;

use App\Repositories\Contracts\StateRepositoryInterface;

class StateService
{
    protected StateRepositoryInterface $repository;

    public function __construct(StateRepositoryInterface $repository)
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
