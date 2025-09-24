<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function index(array $queryParams);
    public function show(int|string $courseId);
    public function store(array $data);
    public function update(array $data);
    public function destroy(int|string $courseId);

}
