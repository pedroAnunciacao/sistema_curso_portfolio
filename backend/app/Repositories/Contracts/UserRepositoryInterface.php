<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function index(array $queryParams);
    public function show(int|string $user_id);
    public function store(array $data);
    public function update(array $data);
    public function destroy(int|string $user_id);
    public function restore(int|string $user_id);
}
