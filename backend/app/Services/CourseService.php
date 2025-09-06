<?php

namespace App\Services;

use App\Repositories\CourseRepository;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;

class CourseService
{
    protected $adapter;
    protected $exceptionClass;
    protected $repository;

    public function __construct(CourseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        return $this->repository->index($request);
    }

    public function show(Course $course)
    {
        return $this->repository->show($course);
    }

    public function store(StoreCourseRequest $request)
    {
        return $this->repository->store($request->course);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        return $this->repository->store($request->course, $course);
    }

    public function destroy(Course $course)
    {
        return $this->repository->destroy($course);
    }
}
