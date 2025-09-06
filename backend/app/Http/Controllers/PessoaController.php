<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Http\Resources\PessoaResource;
use App\Models\Person;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class PessoaController extends Controller
{
    protected $repository;

    public function __construct(Person $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        // Exemplo de autorização (policies)
        // $this->authorize('viewAny', Person::class);

        $perPage = (int) $request->get('page_size') ?? 10;

        $courses = QueryBuilder::for($this->repository->query())
            ->defaultSort('-id')
            ->allowedFilters([
                AllowedFilter::partial('title'),
                AllowedFilter::callback('professor.nome', function (Builder $query, $value) {
                    $query->whereHas('professor', function ($q) use ($value) {
                        $q->where('nome', 'like', "%{$value}%");
                    });
                }),
                AllowedFilter::callback('professor.email', function (Builder $query, $value) {
                    $query->whereHas('professor', function ($q) use ($value) {
                        $q->where('email', $value);
                    });
                }),


            ])
            ->allowedIncludes(['professor', 'lessons', 'students'])
            ->withTrashed()
            ->paginate($perPage);

        return PessoaResource::collection($courses);
    }

    public function show(Person $person)
    {
        // $this->authorize('view', $person);

        return new PessoaResource($person->load(['professor', 'lessons', 'students']));
    }

    public function store(StorePessoaRequest $request)
    {
        $person = $this->repository->create($request->person);

        return new PessoaResource($person);
    }

    public function update(StorePessoaRequest $request, Person $person)
    {
        // $this->authorize('update', $person);

        $person->update($request->person);

        return new PessoaResource($person);
    }

    public function destroy(Person $person)
    {
        // $this->authorize('delete', $person);

        $person->delete();

        return response()->noContent();
    }
}
