<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Person;

interface PersonRepositoryInterface
{
    public function store(array $data);
    public function index(array $queryParams);
    public function show(int|string $personId);
    public function update(array $data);
    public function destroy(int|string $personId);
    public function restore(int|string $personId);

}
