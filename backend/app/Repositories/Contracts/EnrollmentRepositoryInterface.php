<?php

namespace App\Repositories\Contracts;

interface EnrollmentRepositoryInterface
{
    public function index(array $queryParams);
    public function show(int|string $enrollment_id);
    public function store(array $data);
    public function update(array $data);
    public function destroy(int|string $enrollment_id);
    public function restore(int|string $enrollment_id);
}
