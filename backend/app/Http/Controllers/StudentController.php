<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\State\StateResource;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $queryParams = $request->query('queryParams') ?? [];
        $teacher = $this->service->index($queryParams);
        return StateResource::collection($teacher);
    }

    public function show(int $id)
    {
        $teacher =   $this->service->show($id);
        return new StateResource($teacher);
    }
}
