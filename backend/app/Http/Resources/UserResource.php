<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'ativado' => $this->deleted_at === null,
            'pessoa' => new PessoaResource($this->whenLoaded('pessoa')),
            'role' => $this->role,
        ];
    }
}
