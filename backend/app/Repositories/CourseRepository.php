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
                AllowedFilter::callback('professor.nome', function (Builder $query, $value) {
                    $query->whereHas('professor', function ($q) use ($value) {
                        $q->where('nome', 'like', "%{$value}%");
                    });
                }),
                AllowedFilter::callback('professor.email', function (Builder $query, $value) {
                    $query->whereHas('professor', function ($q) use ($value) {
                        $q->where('email', $value);
                    });
                }),


            ])
            ->allowedIncludes(['professor', 'lessons', 'students'])
            ->withTrashed()
            ->paginate($perPage);

        return CourseResource::collection($courses);
    }

    public function show(Course $course)
    {

        return new CourseResource($course->load(['professor', 'lessons', 'students']));
    }

    public function store($request)
    {
        $course = $this->model->create($request->course);

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
