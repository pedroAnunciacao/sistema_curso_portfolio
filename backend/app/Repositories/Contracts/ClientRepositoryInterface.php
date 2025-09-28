<?php

namespace App\Repositories\Contracts;

interface ClientRepositoryInterface
{
    public function index(array $queryParams);
    public function show(int|string $client_id);
    public function store(array $data);
    public function update(array $data);
    public function destroy(int|string  $client_id);
    public function restore(int|string $client_id);

}
