<?php

namespace App\Services;

use App\Repositories\Contracts\CheckoutRepositoryInterface;

class CheckoutService
{
    protected $repository;

    public function __construct(CheckoutRepositoryInterface $repository)
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
        return $this->repository->store($data);
    }

    public function update(array $data)
    {
        return $this->repository->update($data);
    }

    public function destroy(int $id)
    {
        return $this->repository->destroy($id);
    }

        public function restore(int $id)
    {
        return $this->repository->restore($id);
    }


    public function findByTransactionId(int $transaction_id)
    {
        return $this->repository->findByTransactionId($transaction_id);
    }
}
