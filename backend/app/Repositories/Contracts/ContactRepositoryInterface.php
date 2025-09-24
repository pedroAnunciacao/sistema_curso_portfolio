<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface ContactRepositoryInterface
{
    public function createMany(int $personId, array $contacts): void;
    public function updateForPerson(int $personId, array $contacts): void;
}
