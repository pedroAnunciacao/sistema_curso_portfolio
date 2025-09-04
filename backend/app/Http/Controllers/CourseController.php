<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\CourseService;

class CourseController extends Controller
{
    protected $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

        return $this->service->index($request);
    }

    public function show(Course $course)
    {

        return $this->service->show($course);
    }

    public function store(StoreCourseRequest $request)
    {
        return  $this->service->store($request);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {

        return  $this->service->update($request->course, $course);
    }

    public function destroy(Course $course)
    {

        return  $this->service->destroy($course);
    }
}
