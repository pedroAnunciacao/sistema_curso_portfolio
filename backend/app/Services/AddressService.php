<?php

namespace App\Services;

use App\Repositories\Contracts\AddressRepositoryInterface;

class AddressService
{
    protected AddressRepositoryInterface $repository;

    public function __construct(AddressRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
