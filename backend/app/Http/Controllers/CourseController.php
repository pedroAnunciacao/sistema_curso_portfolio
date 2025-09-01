<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class CourseController extends Controller
{
    protected $repository;

    public function __construct(Course $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        // Exemplo de autorização (policies)
        // $this->authorize('viewAny', Course::class);

        $perPage = (int) $request->get('page_size') ?? 10;

        $courses = QueryBuilder::for($this->repository->query())
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
        // $this->authorize('view', $course);

        return new CourseResource($course->load(['professor', 'lessons', 'students']));
    }

    public function store(StoreCourseRequest $request)
    {
        $course = $this->repository->create($request->course);

        return new CourseResource($course);
    }

    public function update(Request $request, Course $course)
    {
        // $this->authorize('update', $course);

        $course->update($request->all());

        return new CourseResource($course);
    }

    public function destroy(Course $course)
    {
        // $this->authorize('delete', $course);

        $course->delete();

        return response()->noContent();
    }
}
