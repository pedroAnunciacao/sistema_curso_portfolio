<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Http\Resources\Person\PersonResource;
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
        $queryParams = $request->query('queryParams') ?? [];
        $persons = $this->service->index($queryParams);
        return PersonResource::collection($persons);
    }

    public function show(int $id)
    {
        $person =   $this->service->show($id);
        return new PersonResource($person);
    }

    public function store(StorePersonRequest $request)
    {
        $person = $this->service->store($request->person);
        return new PersonResource($person);
    }

    public function update(UpdatePersonRequest $request)
    {

        $person = $this->service->update($request->person);
        return new PersonResource($person);
    }

    public function destroy(int $id)
    {
        return  $this->service->destroy($id);
    }
}
