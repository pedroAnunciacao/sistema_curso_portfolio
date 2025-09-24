<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Contracts\ContactRepositoryInterface;
use App\Models\Contact;
use Illuminate\Support\Arr;

class ContactRepository implements ContactRepositoryInterface
{
    public function __construct(private Contact $model)
    {
    }

    public function createMany(int $personId, array $contacts): void
    {
        $contactsData = collect($contacts)->map(function ($contact) use ($personId) {
            return array_merge($contact, ['person_id' => $personId]);
        })->toArray();

        $this->model->insert($contactsData);
    }

    public function updateForPerson(int $personId, array $contacts): void
    {
        $keepIds = Arr::pluck(Arr::where($contacts, fn($item) => Arr::exists($item, 'id')), 'id');
        
        // Remove contatos que não estão na lista
        $this->model->where('person_id', $personId)
            ->whereNotIn('id', $keepIds)
            ->delete();

        // Atualiza ou cria contatos
        foreach ($contacts as $contact) {
            $id = Arr::pull($contact, 'id');
            $this->model->updateOrCreate(
                ['id' => $id, 'person_id' => $personId],
                $contact
            );
        }
    }
}
