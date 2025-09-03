<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Http\Resources\PessoaResource;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class PessoaController extends Controller
{
    protected $repository;

    public function __construct(Pessoa $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        // Exemplo de autorização (policies)
        // $this->authorize('viewAny', Pessoa::class);

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

    public function show(Pessoa $pessoa)
    {
        // $this->authorize('view', $pessoa);

        return new PessoaResource($pessoa->load(['professor', 'lessons', 'students']));
    }

    public function store(StorePessoaRequest $request)
    {
        $pessoa = $this->repository->create($request->pessoa);

        return new PessoaResource($pessoa);
    }

    public function update(StorePessoaRequest $request, Pessoa $pessoa)
    {
        // $this->authorize('update', $pessoa);

        $pessoa->update($request->pessoa);

        return new PessoaResource($pessoa);
    }

    public function destroy(Pessoa $pessoa)
    {
        // $this->authorize('delete', $pessoa);

        $pessoa->delete();

        return response()->noContent();
    }
}
