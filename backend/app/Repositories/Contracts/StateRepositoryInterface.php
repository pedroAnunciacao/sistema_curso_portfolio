<?php

namespace App\Repositories\Contracts;

interface StateRepositoryInterface
{
    public function index(array $queryParams);
    public function show(int|string $state_id);
    public function store(array $data);
    public function update(array $data);

}
