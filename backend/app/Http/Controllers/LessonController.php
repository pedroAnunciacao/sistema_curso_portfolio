<?php

namespace App\Http\Controllers;

use App\Http\Resources\Lesson\LessonResource;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;

use Illuminate\Http\Request;
use App\Services\LessonService;

class LessonController extends Controller
{
    protected $service;

    public function __construct(LessonService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $queryParams = $request->query('queryParams') ?? [];
        $lessons = $this->service->index($queryParams);

        return LessonResource::collection($lessons);
    }

    public function show(int|string $id)
    {
        $lesson = $this->service->show($id);
        return new LessonResource($lesson->load(['course']));
    }

    public function store(StoreLessonRequest $request)
    {

        $lesson = $this->service->store($request->lesson);

        return new LessonResource($lesson);
    }

    public function update(UpdateLessonRequest $request)
    {

        $lesson = $this->service->update($request->lesson);

        return new LessonResource($lesson);
    }

    public function destroy(int|string $id)
    {
        $lesson = $this->service->destroy($id);
        return new LessonResource($lesson);
    }
}
