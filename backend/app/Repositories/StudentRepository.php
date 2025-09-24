<?php

namespace App\Repositories;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;

class StudentRepository implements StudentRepositoryInterface
{

    private $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    public function index(array $queryParams)
    {
        $query = $this->model::query();
        $students = $query->with(['person'])->paginate(10);
        return $students;
    }

    public function show(int|string $studentId)
    {
        $student = $this->model::findOrFail($studentId);
        return $student->load(['person']);
    }

    public function store(array $data)
    {
        $student = $this->model::create($data);
        return $student;
    }

    public function update(array $data)
    {
        $student = $this->model::findOrFail($data['student_id']);
        $student->update($data);
        return $student;
    }

    public function destroy(int|string $studentId)
    {
        $student = $this->model::findOrFail($studentId);
        $student->delete();
        return response()->noContent();
    }
}
