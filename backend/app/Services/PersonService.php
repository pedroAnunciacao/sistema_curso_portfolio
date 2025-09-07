<?php

namespace App\Services;

use App\Repositories\PersonRepository;
use Illuminate\Http\Request;
use App\Models\Person;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdateCourseRequest;

class PersonService
{
    protected $repository;

    public function __construct(PersonRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        return $this->repository->index($request);
    }

    public function show(int $id)
    {
        return $this->repository->show($id);
    }

    public function store(StorePersonRequest $request)
    {
        return $this->repository->store($request);
    }

    public function update(UpdateCourseRequest $request, Person $person)
    {
        return $this->repository->store($request->course, $person);
    }

    public function destroy(Person $person)
    {
        return $this->repository->destroy($person);
    }
}
