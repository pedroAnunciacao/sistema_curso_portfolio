<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface AddressRepositoryInterface
{
    public function createMany(int $personId, array $addresses): void;
    public function updateForPerson(int $personId, array $addresses): void;
}
