<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PessoaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email ?? null,
            'telefone' => $this->telefone ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'professor' => new PessoaResource($this->whenLoaded('teacher')),
            'client' => new PessoaResource($this->whenLoaded('client')),
            'student' => new StudentResource($this->student),
        ];
    }
}
