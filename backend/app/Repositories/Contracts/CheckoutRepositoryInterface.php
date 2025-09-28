<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface CheckoutRepositoryInterface
{
    public function store(array $data);
    public function index(array $queryParams);
    public function show(int|string $id);
    public function update(array $data);
    public function destroy(int|string $id);
    public function restore(int|string $id);
    public function findByTransactionId(string $transactionId);

}
