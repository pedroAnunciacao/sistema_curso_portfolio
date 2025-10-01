<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Teacher\TeacherResource;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected $service;

    public function __construct(TeacherService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $queryParams = $request->query('queryParams') ?? [];
        $teacher = $this->service->index($queryParams);
        return TeacherResource::collection($teacher);
    }

    public function show(int $id)
    {
        $teacher =   $this->service->show($id);
        return new TeacherResource($teacher);
    }
}
