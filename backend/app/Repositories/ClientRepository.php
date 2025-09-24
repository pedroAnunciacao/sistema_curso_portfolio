<?php

namespace App\Repositories;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{

    private $model;

    public function __construct(Client $model)
    {
        $this->model = $model;
    }

    public function index(array $queryParams)
    {
        $query = $this->model::query();
        $cliets = $query->with(['person'])->paginate(10);
        return $cliets;
    }

    public function show(int|string $clietId)
    {
        $cliet = $this->model::findOrFail($clietId);
        return $cliet->load(['person']);
    }

    public function store(array $data)
    {
        $cliet = $this->model::create($data);
        return  $cliet;
    }

    public function update(array $data)
    {

        $cliet = $this->model::findOrFail($data['id']);
        $cliet->update($data);
        return $cliet;
    }

    public function destroy(int|string $clietId)
    {
        $cliet = $this->model::findOrFail($clietId);
        $cliet->delete();
        return response()->noContent();
    }
}
