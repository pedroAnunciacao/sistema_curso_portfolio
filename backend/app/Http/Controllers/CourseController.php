<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\Course\CourseResource;
class CourseController extends Controller
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

        $queryParams = $request->query('queryParams') ?? [];
        $courses = $this->service->index($queryParams);
        return CourseResource::collection($courses);

    }

    public function show(int|string $courseId)
    {
        $course = $this->service->show($courseId);
        return new CourseResource($course);

    }

    public function store(StoreCourseRequest $request)
    {
        $course = $this->service->store($request->course);

        return new CourseResource($course);
    }

    public function byTeacher(Request $request)
    {
        $queryParams = $request->query('queryParams') ?? [];

       $courses = $this->service->byTeacher($queryParams);

        return $courses;
    }

    public function update(UpdateCourseRequest $request)
    {
         $course = $this->service->update($request->course);
        return new CourseResource($course);
    }

    public function destroy(int|string $courseId)
    {
        $course = $this->service->destroy($courseId);
        return new CourseResource($course);
    }
}
