<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Services\PersonService;

class PersonController extends Controller
{
    protected $service;

    public function __construct(PersonService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

        return $this->service->index($request);
    }

    public function show(int $id)
    {

        return $this->service->show($id);
    }

    public function store(StorePersonRequest $request)
    {
        return  $this->service->store($request);
    }

    public function update(UpdateCourseRequest $request, Person $person)
    {

        return  $this->service->update($request->person, $person);
    }

    public function destroy(Person $person)
    {

        return  $this->service->destroy($person);
    }
}
