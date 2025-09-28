<?php

namespace App\Repositories\Contracts;

interface CourseRepositoryInterface
{
    public function index(array $queryParams);
    public function show(int|string $course_id);
    public function store(array $data);
    public function update(array $data);
    public function byTeacher(array $queryParams);
    public function restore(int|string $course_id);
    public function destroy(int|string $course_id);


}
