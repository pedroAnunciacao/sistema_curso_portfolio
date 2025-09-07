<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\StorePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PersonRepository
{
    private $model;

    public function __construct(Person $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {

        $perPage = (int) $request->get('page_size') ?? 10;

        $person = QueryBuilder::for($this->model::class)
            ->defaultSort('-id')
            ->allowedFields('nome')
            ->allowedFilters([
                'nome',
                AllowedFilter::exact('pessoa_tipo_id'),
                AllowedFilter::callback(
                    'cpf_cnpj',
                    function (Builder $query, $value): void {
                        $query->whereRaw('cpf_cnpj::varchar ilike ?', ["%$value%"]);
                    }
                ),
                AllowedFilter::callback(
                    'c_p_f/_c_n_p_j',
                    function (Builder $query, $value): void {
                        $cpfCnpj = preg_replace('/\D/', '', $value);
                        $query->whereRaw('cpf_cnpj::varchar ilike ?', ["%$cpfCnpj%"]);
                    }
                ),
                AllowedFilter::callback('search', function (Builder $query, $value): void {
                    $query->where('nome', 'ilike', "%$value%")
                        ->orWhere('nome_social', 'ilike', "%$value%")
                        ->orWhere('cpf_cnpj', 'like', "%$value%");
                }),
            ])
            ->allowedSorts(['nome'])
            ->allowedIncludes([
                'addresses.city.state',
            ])
            ->withTrashed()
            ->paginate($perPage);

        return PersonResource::collection($person);
    }

    public function show(int $id)
    {
        $pessoa = QueryBuilder::for($this->model::class)
            ->allowedIncludes([
                'documentos.file',
                'documentos.cidade',
                'documentos.estado',
                'enderecos.cidade.estado',
                'contatos',
                'estadoCivil',
                'sexo',
                'genero',
                'necessidadesEspeciais',
                'filiacao.parentesco',
                'cor',
                'nacionalidade',
                'alunos',
                'professores',
                'file',
                'externalReferences',
            ])
            ->where('id', $id)
            ->withTrashed()
            ->firstOrFail();


        return new PersonResource($pessoa);
    }

    public function store(StorePersonRequest $request)
    {
        $data = $request->input('person');

        $person = DB::transaction(function () use ($data) {
            $resource = $this->model->create($data);

            collect(['addresses', 'contacts'])->each(function ($relationship) use ($data, $resource) {
                $items = $data[$relationship] ?? [];
                $resource->{$relationship}()->createMany($items);
            });

            return $resource;
        });

        return new PersonResource($person);
    }

    public function update(StorePersonRequest $request, int $id)
    {
        $person = $this->model::with(['addresses', 'contacts'])->findOrFail($id);
        $data = $request->input('person');

        DB::transaction(function () use ($person, $data) {
            collect(['addresses', 'contacts'])->each(function ($relationship) use ($data, $person) {
                $items = $data[$relationship] ?? [];
                $keepIds = Arr::pluck(Arr::where($items, fn($item) => Arr::exists($item, 'id')), 'id');

                $person->{$relationship}()->whereNotIn('id', $keepIds)->delete();

                foreach ($items as $item) {
                    $id = Arr::pull($item, 'id');
                    $person->{$relationship}()->updateOrCreate(['id' => $id], $item);
                }
            });

            $person->update($data);
        });

        return new PersonResource($person);
    }

    public function destroy(Person $person)
    {

        $person->delete();

        return new PersonResource($person);
    }

    public function restore(int $id)
    {
        $pessoa = $this->model::withTrashed()->findOrFail($id);


        $pessoa->restore();

        return new PersonResource($pessoa);
    }
}
