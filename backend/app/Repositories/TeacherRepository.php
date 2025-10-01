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
        $teachers = $query->with(['person'])->paginate(10);
        return $teachers;
    }

    public function show(int|string $id)
    {
        $teacher = $this->model::findOrFail($id);
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

    public function destroy(int|string $id)
    {
        $teacher = $this->model->findOrFail($id);
        $teacher->delete();
        return response()->noContent();
    }

    public function restore(int|string $id)
    {
        $teacher = $this->model->withTrashed()->findOrFail($id);
        $teacher->restore();
        return $teacher;
    }    
}
