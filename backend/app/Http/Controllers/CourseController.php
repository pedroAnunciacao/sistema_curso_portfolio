<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Http\Requests\Course;

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
        return $this->service->index($queryParams);
    }

    public function show(int|string $courseId)
    {
        return $this->service->show($courseId);
    }

    public function store(Request $request)
    {
        return $this->service->store($request->course);
    }


    public function byTeacher(Request $request)
    {
        $queryParams = $request->query('queryParams') ?? [];

        return $this->service->byTeacher($queryParams);
    }


    public function update(Request $request)
    {
        return $this->service->update($request->course);
    }

    public function destroy(int|string $courseId)
    {
        return $this->service->destroy($courseId);
    }
}
