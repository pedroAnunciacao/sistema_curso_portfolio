<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Repositories\Filters\FilterResolver;
use App\Repositories\Filters\LikeNameFilter;
use App\Repositories\Includes\Includes;

class LessonRepository implements EnrollmentRepositoryInterface
{
    protected $model;

    public function __construct(Lesson $model)
    {
        $this->model = $model;
    }

    public function index(array $queryParams)
    {

        $query = $this->model->query();
        $perPage = (int) isset($queryParams['page_size']) ?? 10;
        $filters = [
            'title' => LikeNameFilter::class,
        ];

        $query =  !empty($queryParams['includes']) ? Includes::applyIcludes($query, $queryParams['includes']) : $query;
        $query = FilterResolver::applyFilters($query, $filters, $queryParams);

        $lessons = $query->paginate($perPage);
        return $lessons;
    }

    public function show(int|string $state_id)
    {
        $query = $this->model->findOrFail($state_id);
        $lesson = $query->load(['course']);
        return $lesson;
    }

    public function store(array $data)
    {
        return $this->model::create($data);
    }

    public function update(array $data)
    {
        $query = $this->model::findOrFail($data['id']);
        $lesson = $query->update($data);
        return $lesson;
    }

    public function destroy(int|string $id)
    {
        $lesson = $this->model->findOrFail($id);
        $lesson->delete();
        return response()->noContent();
    }

    public function restore(int|string $id)
    {
        $lesson = $this->model->withTrashed()->findOrFail($id);
        $lesson->restore();
        return $lesson;
    }
}
