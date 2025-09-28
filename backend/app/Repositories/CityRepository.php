<?php

namespace App\Repositories;

use App\Models\City;
use App\Repositories\Contracts\CityRepositoryInterface;
use App\Repositories\Filters\FilterResolver;
use App\Repositories\Filters\LikeNameFilter;
use App\Repositories\Includes\Includes;

class CityRepository implements CityRepositoryInterface
{

    private $model;

    public function __construct(City $model)
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

        $cities = $query->paginate($perPage);
        return $cities;
    }

    public function show(int|string $state_id)
    {
        $query = $this->model->findOrFail($state_id);
        $city = $query->load(['cities']);
        return $city;
    }

    public function store(array $data)
    {
        return $this->model::create($data);
    }

    public function update(array $data)
    {
        $query = $this->model::findOrFail($data['id']);
        $city = $query->update($data);
        return $city;
    }

}
