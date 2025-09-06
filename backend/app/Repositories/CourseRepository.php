<?php

namespace App\Repositories;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class CourseRepository
{
    protected $model;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {

        $perPage = (int) $request->get('page_size') ?? 10;

        $courses = QueryBuilder::for($this->model->query())
            ->defaultSort('-id')
            ->allowedFilters([
                AllowedFilter::partial('title'),
                AllowedFilter::callback('teacher.nome', function (Builder $query, $value) {
                    $query->whereHas('teacher', function ($q) use ($value) {
                        $q->where('nome', 'like', "%{$value}%");
                    });
                }),
                AllowedFilter::callback('teacher.email', function (Builder $query, $value) {
                    $query->whereHas('teacher', function ($q) use ($value) {
                        $q->where('email', $value);
                    });
                }),


            ])
            ->allowedIncludes(['teacher.person', 'lessons', 'students'])
            ->withTrashed()
            ->paginate($perPage);

        return CourseResource::collection($courses);
    }

    public function show(Course $course)
    {

        return new CourseResource($course->load(['teacher.person', 'lessons', 'students']));
    }

    public function store(array $courseFormData)
    {
        $course = $this->model->create($courseFormData);

        return new CourseResource($course);
    }

    public function update(array $data, Course $course)
    {

        $course->update($data);

        return new CourseResource($course);
    }

    public function destroy(Course $course)
    {

        $course->delete();

        return response()->noContent();
    }
}
