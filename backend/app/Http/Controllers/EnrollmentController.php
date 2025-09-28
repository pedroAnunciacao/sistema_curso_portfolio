<?php

namespace App\Http\Controllers;

use App\Http\Resources\Enrollment\EnrollmentResource;
use App\Http\Requests\Enrollment\StoreEnrollmenrRequest;
use App\Http\Requests\Enrollment\UpdateEnrollmentRequest;
use Illuminate\Http\Request;
use App\Services\EnrollmentService;

class EnrollmentController extends Controller
{
    protected $service;

    public function __construct(EnrollmentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $queryParams = $request->query('queryParams') ?? [];
        $enrollments = $this->service->index($queryParams);
        return EnrollmentResource::collection($enrollments);
    }

    public function show(int|string $id)
    {
        $enrollment = $this->service->show($id);
        return new EnrollmentResource($enrollment->load(['person', 'course']));
    }

    public function store(StoreEnrollmenrRequest $request)
    {

        $enrollment = $this->service->store($request->enrollment);

        return new EnrollmentResource($enrollment);
    }

    public function update(UpdateEnrollmentRequest $request)
    {

        $enrollment = $this->service->update($request->enrollment);
        return new EnrollmentResource($enrollment);
    }

    public function destroy(int|string $id)
    {

        $enrollment = $this->service->destroy($id);
        return new EnrollmentResource($enrollment);
    }
}
