<?php

namespace App\Repositories\Contracts;

interface StudentRepositoryInterface
{
    public function index(array $queryParams);
    public function show(int|string $studentId);
    public function store(array $data);
    public function update(array $data);
    public function destroy(int|string $studentId);
}
