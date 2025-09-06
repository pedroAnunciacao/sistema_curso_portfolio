<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PessoaRepository extends Controller
{
    private $model;

    public function __construct(Person $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {

        $perPage = (int) $request->get('page_size') ?? 10;

        $pessoas = QueryBuilder::for($this->model::class)
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
                'documentos',
                'documentos.cidade',
                'documentos.estado',
                'enderecos.cidade.estado',
                'contatos',
                'estadoCivil',
                'sexo',
                'genero',
                'externalReferences',
            ])
            ->withTrashed()
            ->paginate($perPage);

        return PersonResource::collection($pessoas);
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

    public function store(PersonRequest $request)
    {

        $data = $request->pessoa;

        $pessoa = DB::transaction(function () use ($data) {
            $necessidadesEspeciaisIds = $data['necessidades_especiais_ids'] ?? [];
            $resource = $this->model->create($data);
            $resource->necessidadesEspeciais()->attach($necessidadesEspeciaisIds);

            collect($data)->only('contatos', 'documentos', 'enderecos', 'filiacao')
                ->each(function ($value, $relationship) use ($resource): void {
                    $instances = $resource->{$relationship}()->createMany($value);

                    if ($relationship === 'documentos') {
                        $instances->each(function ($documento, $index) use ($value): void {
                            $fileId = $value[$index]['file_id'] ?? null;

                            if ($fileId) {
                                $documento->file()->create(['original_id' => $fileId]);
                            }
                        });
                    }
                });

            return $resource;
        });

        return new PersonResource($pessoa);
    }

    public function update(PersonRequest $request, int $id)
    {
        $pessoa = $this->model::whereKey($id)->withTrashed()->firstOrFail();


        $data = $request->pessoa;

        DB::transaction(function () use ($pessoa, $data): void {
            $necessidadesEspeciaisIds = $data['necessidades_especiais_ids'] ?? [];
            $pessoa->necessidadesEspeciais()->sync($necessidadesEspeciaisIds);

            collect(['documentos', 'enderecos', 'contatos', 'filiacao'])->each(
                function ($relationship) use ($data, $pessoa): void {
                    $value = $data[$relationship] ?? [];
                    $keepIds = Arr::pluck(
                        Arr::where($value, fn ($item) => Arr::exists($item, 'id')),
                        'id'
                    );

                    $items = $pessoa->{$relationship}()->whereNotIn('id', $keepIds);

                    if ($relationship === 'documentos') {
                        $items->each(fn ($documento) => $documento->file()->delete());
                    }

                    $items->delete();

                    collect($value)->each(function ($item) use ($pessoa, $relationship): void {
                        $id = Arr::pull($item, 'id');
                        $instance = $pessoa->{$relationship}()->updateOrCreate(['id' => $id], $item);

                        if ($relationship === 'documentos') {
                            $fileId = $item['file_id'] ?? null;

                            if ($fileId) {
                                $instance->file()->create(['original_id' => $fileId]);
                            }
                        }
                    });
                }
            );

            $pessoa->update($data);
        });

        return new PersonResource($pessoa);
    }

    public function destroy(Person $pessoa)
    {

        $pessoa->delete();

        return new PersonResource($pessoa);
    }

    public function restore(int $id)
    {
        $pessoa = $this->model::withTrashed()->findOrFail($id);


        $pessoa->restore();

        return new PersonResource($pessoa);
    }
}
