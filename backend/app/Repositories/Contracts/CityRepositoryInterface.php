<?php

namespace App\Repositories\Contracts;

interface CityRepositoryInterface
{
    public function index(array $queryParams);
    public function show(int|string $city_id);
    public function store(array $data);
    public function update(array $data);

}
