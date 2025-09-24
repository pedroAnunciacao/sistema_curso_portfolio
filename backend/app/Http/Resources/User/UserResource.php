<?php

declare(strict_types=1);

namespace App\Http\Resources\User;
use App\Http\Resources\Person\PersonResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'ativado' => $this->deleted_at === null,
            'person' => new PersonResource($this->whenLoaded('person')),
        ];
    }
}
