<?php

namespace App\Repositories;

use App\Models\State;
use App\Repositories\Contracts\StateRepositoryInterface;
use App\Repositories\Filters\FilterResolver;
use App\Repositories\Filters\LikeNameFilter;
use App\Repositories\Includes\Includes;

class StateRepository implements StateRepositoryInterface
{

    private $model;

    public function __construct(State $model)
    {
        $this->model = $model;
    }

    public function index(array $queryParams)
    {

        $query = $this->model->query();
        $perPage = (int) isset($queryParams['page_size']) ?? 10;
        $filters = [
            'name' => LikeNameFilter::class,
        ];

        $query =  !empty($queryParams['includes']) ? Includes::applyIcludes($query, $queryParams['includes']) : $query;
        $query = FilterResolver::applyFilters($query, $filters, $queryParams);

        $states = $query->paginate($perPage);
        return $states;
    }

    public function show(int|string $id)
    {
        $query = $this->model->findOrFail($id);
        $state = $query->load(['cities']);
        return $state;
    }

    public function store(array $data)
    {
        return $this->model::create($data);
    }

    public function update(array $data)
    {
        $query = $this->model::findOrFail($data['id']);
        $state = $query->update($data);
        return $state;
    }

}
