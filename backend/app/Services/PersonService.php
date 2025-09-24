<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Repositories\Contracts\PersonRepositoryInterface;

class PersonService
{
    protected PersonRepositoryInterface $repository;

    public function __construct(PersonRepositoryInterface $repository)
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

    public function store(array $data)
    {
        if(isset($data['client'])){

        }
        return $this->repository->store($data);
    }

    public function update(array $data)
    {

        return $this->repository->update($data);
    }

    public function destroy(int $personId)
    {
        return $this->repository->destroy($personId);
    }
}
