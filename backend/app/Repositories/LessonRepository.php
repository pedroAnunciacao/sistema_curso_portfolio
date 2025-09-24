<?php

namespace App\Repositories;

use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class LessonRepository
{
    protected $repository;

    public function __construct(Lesson $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {

        $perPage = (int) $request->get('page_size') ?? 10;

        $lessons = QueryBuilder::for($this->repository->query())
            ->defaultSort('-id')
            ->allowedFilters([
                AllowedFilter::partial('title'),
                AllowedFilter::callback('course', function (Builder $query, $value) {
                    $query->whereHas('course', function ($q) use ($value) {
                        $q->where('title', 'ilike', "%$value%");
                    });
                }),
            ])
            ->allowedIncludes(['course'])
            ->withTrashed()
            ->paginate($perPage);

        return LessonResource::collection($lessons);
    }

    public function show(Lesson $lesson)
    {

        return new LessonResource($lesson->load(['course']));
    }

    public function store(Request $request)
    {

        $lesson = $this->repository->create($request->all());

        return new LessonResource($lesson);
    }

    public function update(Request $request, Lesson $lesson)
    {

        $lesson->update($request->all());

        return new LessonResource($lesson);
    }

    public function destroy(Lesson $lesson)
    {

        $lesson->delete();

        return response()->noContent();
    }
}
