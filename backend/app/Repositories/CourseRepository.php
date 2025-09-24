<?php

namespace App\Repositories;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Filters\FilterResolver;
use App\Repositories\Filters\LikeNameFilter;
use App\Repositories\Filters\ExactMatchFilter;
use App\Repositories\Includes\Includes;

class CourseRepository implements CourseRepositoryInterface
{

    private $model;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    public function index(array $queryParams)
    {

        $filters = [
            'title' => LikeNameFilter::class,
            'teacher.person.name' => LikeNameFilter::class,
        ];

        $query = $this->model::query();

        $query = FilterResolver::applyFilters($query, $filters, $queryParams);

        $courses = $query->with(['teacher.person', 'lessons'])->paginate(10);
        return CourseResource::collection($courses);
    }

    public function show(int|string $courseId)
    {
        $course = $this->model::findOrFail($courseId);
        return new CourseResource($course->load(['teacher.person', 'lessons', 'students']));
    }

    public function byTeacher(array $queryParams)
    {

        $filters = [
            'teacher_id' => ExactMatchFilter::class,
        ];

        $query = $this->model::query();
        $query = Includes::applyIcludes($query, $queryParams['includes']);
        $query = FilterResolver::applyFilters($query, $filters, $queryParams);
        $courses = $query->paginate($queryParams['page_size'] ?? 10);

        return CourseResource::collection($courses);
    }

    public function store(array $data)
    {
        $course = $this->model::create($data);
        return new CourseResource($course);
    }

    public function update(array $data)
    {
        $course = $this->model::findOrFail($data['id']);
        $course->update($data);
        return new CourseResource($course);
    }

    public function destroy(int|string $courseId)
    {
        $course = $this->model::findOrFail($courseId);
        $course->delete();
        return response()->noContent();
    }
}
