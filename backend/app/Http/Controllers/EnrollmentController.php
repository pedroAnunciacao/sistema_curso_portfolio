<?php

namespace App\Http\Controllers;

use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentController extends Controller
{
    protected $repository;

    public function __construct(Enrollment $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Enrollment::class);

        $perPage = (int) $request->get('page_size') ?? 10;

        $enrollments = QueryBuilder::for($this->repository->query())
            ->defaultSort('-id')
            ->allowedFilters([
                AllowedFilter::callback('course', function (Builder $query, $value) {
                    $query->whereHas('course', function ($q) use ($value) {
                        $q->where('title', 'ilike', "%$value%");
                    });
                }),
                AllowedFilter::callback('student', function (Builder $query, $value) {
                    $query->whereHas('person', function ($q) use ($value) {
                        $q->where('nome', 'ilike', "%$value%");
                    });
                }),
            ])
            ->allowedIncludes(['person', 'course'])
            ->withTrashed()
            ->paginate($perPage);

        return EnrollmentResource::collection($enrollments);
    }

    public function show(Enrollment $enrollment)
    {
        $this->authorize('view', $enrollment);

        return new EnrollmentResource($enrollment->load(['person', 'course']));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Enrollment::class);

        $enrollment = $this->repository->create($request->all());

        return new EnrollmentResource($enrollment);
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);

        $enrollment->update($request->all());

        return new EnrollmentResource($enrollment);
    }

    public function destroy(Enrollment $enrollment)
    {
        $this->authorize('delete', $enrollment);

        $enrollment->delete();

        return response()->noContent();
    }
}
