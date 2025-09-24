<?php

namespace App\Repositories;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Filters\FilterResolver;
use App\Repositories\Filters\LikeNameFilter;
use App\Repositories\Filters\ExactMatchFilter;
use App\Repositories\Includes\Includes;

class ClientRepository implements CourseRepositoryInterface
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

    public function show(string $courseId)
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

    public function update(string $courseId, array $data)
    {
        $course = $this->model::findOrFail($courseId);
        $course->update($data);
        return new CourseResource($course);
    }

    public function destroy(string $courseId)
    {
        $course = $this->model::findOrFail($courseId);
        $course->delete();
        return response()->noContent();
    }
}
