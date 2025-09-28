<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Repositories\Filters\FilterResolver;
use App\Repositories\Filters\LikeNameFilter;
use App\Repositories\Includes\Includes;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{

    private $model;

    public function __construct(Enrollment $model)
    {
        $this->model = $model;
    }

    public function index(array $queryParams)
    {

        $query = $this->model->query();
        $perPage = (int) isset($queryParams['page_size']) ?? 10;
        $filters = [
            'name' => LikeNameFilter::class,
        ];

        $query =  !empty($queryParams['includes']) ? Includes::applyIcludes($query, $queryParams['includes']) : $query;
        $query = FilterResolver::applyFilters($query, $filters, $queryParams);

        $enrollments = $query->paginate($perPage);
        return $enrollments;
    }

    public function show(int|string $id)
    {
        $query = $this->model->findOrFail($id);
        $enrollment = $query->load(['course', 'student']);
        return $enrollment;
    }

    public function store(array $data)
    {
        return $this->model::create($data);
    }

    public function update(array $data)
    {
        $query = $this->model::findOrFail($data['id']);
        $enrollment = $query->update($data);
        return $enrollment;
    }

    public function destroy(int|string $id)
    {
        $enrollment = $this->model->findOrFail($id);
        $enrollment->delete();
        return response()->noContent();
    }

    public function restore(int|string $id)
    {
        $enrollment = $this->model->withTrashed()->findOrFail($id);
        $enrollment->restore();
        return $enrollment;
    }
}
