<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Contracts\AddressRepositoryInterface;
use App\Models\Address;
use Illuminate\Support\Arr;

class AddressRepository implements AddressRepositoryInterface
{
    public function __construct(private Address $model)
    {
    }

    public function createMany(int $personId, array $addresses): void
    {
        $addressesData = collect($addresses)->map(function ($address) use ($personId) {
            return array_merge($address, ['person_id' => $personId]);
        })->toArray();

        $this->model->insert($addressesData);
    }

    public function updateForPerson(int $personId, array $addresses): void
    {
        $keepIds = Arr::pluck(Arr::where($addresses, fn($item) => Arr::exists($item, 'id')), 'id');
        
        // Remove endereços que não estão na lista
        $this->model->where('person_id', $personId)
            ->whereNotIn('id', $keepIds)
            ->delete();

        // Atualiza ou cria endereços
        foreach ($addresses as $address) {
            $id = Arr::pull($address, 'id');
            $this->model->updateOrCreate(
                ['id' => $id, 'person_id' => $personId],
                $address
            );
        }
    }
}
