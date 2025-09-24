<?php

namespace App\Repositories;

use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Repositories\Contracts\TeacherRepositoryInterface;

class TeacherRepository implements TeacherRepositoryInterface
{

    private $model;

    public function __construct(Teacher $model)
    {
        $this->model = $model;
    }

    public function index(array $queryParams)
    {

        $query = $this->model::query();
        $teachers = $query->with(['person;'])->paginate(10);
        return $teachers;
    }

    public function show(int|string $teacherId)
    {
        $teacher = $this->model::findOrFail($teacherId);
        return $teacher->load(['person']);
    }

    public function store(array $data)
    {
        $teacher = $this->model::create($data);
        return $teacher;
    }

    public function update(array $data)
    {
        $teacher = $this->model::findOrFail($data['teacher_id']);
        $teacher->update($data);
        return $teacher;
    }

    public function destroy(int|string $teacherId)
    {
        $teacher = $this->model::findOrFail($teacherId);
        $teacher->delete();
        return response()->noContent();
    }
}
