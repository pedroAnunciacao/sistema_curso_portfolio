<?php

namespace App\Repositories;

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
        $query = $this->model->query();
        $students = $query->with(['person'])->paginate(10);
        return $students;
    }

    public function show(int|string $id)
    {
        $student = $this->model->findOrFail($id);
        return $student->load(['person']);
    }

    public function store(array $data)
    {
        $student = $this->model->create($data);
        return $student;
    }

    public function update(array $data)
    {
        $student = $this->model->findOrFail($data['student_id']);
        $student->update($data);
        return $student;
    }

    public function destroy(int|string $id)
    {
        $student = $this->model->findOrFail($id);
        $student->delete();
        return response()->noContent();
    }


    public function restore(int|string $id)
    {
        $pessoa = $this->model->withTrashed()->findOrFail($id);
        $pessoa->restore();
        return $pessoa;
    }
}
