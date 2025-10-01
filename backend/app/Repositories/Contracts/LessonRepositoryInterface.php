<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface LessonRepositoryInterface
{
    public function store(array $data);
    public function index(array $queryParams);
    public function show(int|string $lesson_id);
    public function update(array $data);
    public function destroy(int|string $lesson_id);
    public function restore(int|string $lesson_id);

}
