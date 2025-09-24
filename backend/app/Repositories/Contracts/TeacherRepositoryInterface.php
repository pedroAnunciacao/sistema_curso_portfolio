<?php

namespace App\Repositories\Contracts;

interface TeacherRepositoryInterface
{
    public function index(array $queryParams);
    public function show(int|string $teacherId);
    public function store(array $data);
    public function update(array $data);
    public function destroy(int|string $teacherId);

}
